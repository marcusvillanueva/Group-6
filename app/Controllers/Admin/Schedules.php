<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CustomerModel;
use App\Models\EmployeeModel;
use App\Models\NotificationModel;
use App\Models\PaymentModel;
use App\Models\ScheduleModel;
use App\Models\SettingModel;

// Schedule overview for Admins and Dispatchers: calendar of all bookings,
// assign technicians, approve/decline requests, adjust schedules.
class Schedules extends BaseController
{
    // GET admin/schedules?month=2026-10&status=pending
    public function index()
    {
        $month = (string) $this->request->getGet('month');
        if (! preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = date('Y-m');
        }
        $status = (string) $this->request->getGet('status');

        $firstDay = $month . '-01';
        $lastDay  = date('Y-m-t', strtotime($firstDay));

        $query = (new ScheduleModel())->withDetails()
            ->where('schedules.schedule_date >=', $firstDay)
            ->where('schedules.schedule_date <=', $lastDay);
        if (in_array($status, config('SunSon')->scheduleStatuses, true)) {
            $query->where('schedules.status', $status);
        }
        $bookings = $query->orderBy('schedules.schedule_time', 'ASC')->findAll();

        // Group bookings by date so each calendar box can show its own.
        $byDate = [];
        foreach ($bookings as $booking) {
            $byDate[$booking['schedule_date']][] = $booking;
        }

        return view('admin/schedules', [
            'title'     => 'Schedule Overview | Sun Son Solar',
            'active'    => 'schedules',
            'month'     => $month,
            'status'    => $status,
            'weeks'     => $this->calendarWeeks($firstDay),
            'byDate'    => $byDate,
            'prevMonth' => date('Y-m', strtotime($firstDay . ' -1 month')),
            'nextMonth' => date('Y-m', strtotime($firstDay . ' +1 month')),
            'pending'   => (new ScheduleModel())->withDetails()->where('schedules.status', 'pending')
                ->orderBy('schedules.schedule_date', 'ASC')->findAll(),
        ]);
    }

    // GET admin/schedules/{id}
    public function manage(int $id)
    {
        $booking = (new ScheduleModel())->withDetails()->where('schedules.schedule_id', $id)->first();
        if ($booking === null) {
            return redirect()->to(site_url('admin/schedules'))->with('error', 'Booking not found.');
        }

        return view('admin/schedule_manage', [
            'title'       => 'Booking #' . $id . ' | Sun Son Solar',
            'active'      => 'schedules',
            'booking'     => $booking,
            'customer'    => (new CustomerModel())->find($booking['customer_id']),
            'technicians' => (new EmployeeModel())->fieldStaff(),
            'payment'     => (new PaymentModel())->where('schedule_id', $id)->first(),
        ]);
    }

    // POST admin/schedules/{id} - approve/decline, assign technician, adjust date and time
    public function update(int $id)
    {
        $model   = new ScheduleModel();
        $booking = $model->withDetails()->where('schedules.schedule_id', $id)->first();
        if ($booking === null) {
            return redirect()->to(site_url('admin/schedules'))->with('error', 'Booking not found.');
        }

        $data  = $this->request->getPost(['status', 'technician_id', 'schedule_date', 'schedule_time', 'admin_note']);
        $rules = [
            'status'        => ['rules' => 'required|in_list[' . implode(',', config('SunSon')->scheduleStatuses) . ']'],
            'technician_id' => ['rules' => 'permit_empty|is_not_unique[employees.employee_id]', 'errors' => ['is_not_unique' => 'Please choose a technician from the list.']],
            'schedule_date' => ['rules' => 'required|valid_date[Y-m-d]', 'errors' => ['required' => 'Please choose a date.']],
            'schedule_time' => ['rules' => 'required|in_list[' . implode(',', array_keys(config('SunSon')->timeSlots)) . ']', 'errors' => ['in_list' => 'Please choose a listed time.']],
            'admin_note'    => ['rules' => 'permit_empty|max_length[255]'],
        ];

        if (! $this->validateData($data, $rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $technicianId = $data['technician_id'] === '' || $data['technician_id'] === null ? null : (int) $data['technician_id'];
        if ($data['status'] === 'approved' && $technicianId === null) {
            return redirect()->back()->withInput()->with('error', 'Please assign a technician before approving.');
        }

        $model->update($id, [
            'status'        => $data['status'],
            'technician_id' => $technicianId,
            'schedule_date' => $data['schedule_date'],
            'schedule_time' => $data['schedule_time'],
            'admin_note'    => trim((string) $data['admin_note']),
        ]);

        // Tell the customer what changed (if the Admin has this switched on).
        $changes = [];
        if ($data['status'] !== $booking['status']) {
            $changes[] = 'Status: ' . ucfirst($data['status']);
        }
        if ($data['schedule_date'] !== $booking['schedule_date'] || $data['schedule_time'] !== $booking['schedule_time']) {
            $changes[] = 'New schedule: ' . nice_date($data['schedule_date']) . ' at ' . nice_time($data['schedule_time']);
        }
        if ($technicianId !== null && $technicianId !== (int) $booking['technician_id']) {
            $tech      = (new EmployeeModel())->find($technicianId);
            $changes[] = 'Technician: ' . $tech['firstname'] . ' ' . $tech['lastname'];
        }
        if (trim((string) $data['admin_note']) !== '' && trim((string) $data['admin_note']) !== (string) $booking['admin_note']) {
            $changes[] = 'Note: ' . trim((string) $data['admin_note']);
        }

        if ($changes !== [] && (new SettingModel())->isOn('auto_schedule_updates')) {
            (new NotificationModel())->send(
                (int) $booking['customer_id'],
                'update',
                'Booking #' . $id . ' updated: ' . $booking['service_name'],
                implode("\n", $changes)
            );
        }

        return redirect()->to(site_url('admin/schedules/' . $id))->with('success', 'Booking #' . $id . ' saved.');
    }

    // POST admin/schedules/{id}/payment - record payment for a completed service
    public function recordPayment(int $id)
    {
        $booking = (new ScheduleModel())->withDetails()->where('schedules.schedule_id', $id)->first();
        if ($booking === null || $booking['status'] !== 'completed' || $booking['payment_id']) {
            return redirect()->back()->with('error', 'Payments can only be recorded once, for completed services.');
        }

        $data  = $this->request->getPost(['amount', 'method']);
        $rules = [
            'amount' => ['rules' => 'required|decimal|greater_than[0]', 'errors' => ['required' => 'Enter the amount paid.', 'decimal' => 'Amount must be a number.', 'greater_than' => 'Amount must be more than zero.']],
            'method' => ['rules' => 'required|in_list[' . implode(',', config('SunSon')->paymentMethods) . ']', 'errors' => ['required' => 'Choose a payment method.']],
        ];
        if (! $this->validateData($data, $rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // One payment per booking, so the booking number makes the receipt number unique.
        $receiptNo = 'SSS-' . date('Ymd') . '-' . str_pad((string) $id, 5, '0', STR_PAD_LEFT);

        (new PaymentModel())->insert([
            'schedule_id' => $id,
            'customer_id' => $booking['customer_id'],
            'amount'      => $data['amount'],
            'method'      => $data['method'],
            'receipt_no'  => $receiptNo,
            'recorded_by' => session('name'),
            'paid_at'     => date('Y-m-d H:i:s'),
        ]);

        if ((new SettingModel())->isOn('auto_payment_receipts')) {
            (new NotificationModel())->send(
                (int) $booking['customer_id'],
                'update',
                'Payment received - receipt ' . $receiptNo,
                'We received ' . peso($data['amount']) . ' via ' . $data['method'] . ' for your ' . $booking['service_name'] . '. Your receipt is in the History tab.'
            );
        }

        return redirect()->to(site_url('admin/schedules/' . $id))->with('success', 'Payment recorded. Receipt ' . $receiptNo . ' is now in the customer\'s history.');
    }

    // Builds the calendar as weeks (Sunday to Saturday); null = empty box.
    private function calendarWeeks(string $firstDay): array
    {
        $daysInMonth = (int) date('t', strtotime($firstDay));
        $blankBoxes  = (int) date('w', strtotime($firstDay));

        $boxes = array_fill(0, $blankBoxes, null);
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $boxes[] = date('Y-m-', strtotime($firstDay)) . str_pad((string) $day, 2, '0', STR_PAD_LEFT);
        }
        while (count($boxes) % 7 !== 0) {
            $boxes[] = null;
        }

        return array_chunk($boxes, 7);
    }
}
