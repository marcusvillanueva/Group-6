<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\CustomerModel;
use App\Models\ScheduleModel;
use App\Models\ServiceModel;

// Customers view and manage their scheduled services.
class Schedules extends BaseController
{
    public function index()
    {
        $customer = (new CustomerModel())->find($this->userId());

        $data = [
            'title'     => 'My Schedules | Sun Son Solar',
            'active'    => 'schedules',
            'schedules' => (new ScheduleModel())->withDetails()
                ->where('schedules.customer_id', $this->userId())
                ->orderBy('schedules.schedule_date', 'DESC')
                ->findAll(),
            'services'  => (new ServiceModel())->where('is_available', 1)->findAll(),
            'address'   => $customer['address'],
            'minDate'   => date('Y-m-d', strtotime('+1 day')),
        ];

        return view('customer/schedules', $data);
    }

    // POST customer/schedules - book a new service
    public function book()
    {
        $data = $this->request->getPost(['service_id', 'schedule_date', 'schedule_time', 'address', 'notes']);

        $rules = [
            'service_id'    => ['rules' => 'required|is_not_unique[services.service_id]', 'errors' => ['required' => 'Please choose a service.', 'is_not_unique' => 'Please choose a service.']],
            'schedule_date' => ['rules' => 'required|valid_date[Y-m-d]', 'errors' => ['required' => 'Please choose a date.', 'valid_date' => 'Please choose a valid date.']],
            'schedule_time' => ['rules' => 'required|in_list[' . implode(',', array_keys(config('SunSon')->timeSlots)) . ']', 'errors' => ['required' => 'Please choose a time.', 'in_list' => 'Please choose one of the listed times.']],
            'address'       => ['rules' => 'required|max_length[255]', 'errors' => ['required' => 'Please enter the service address.']],
            'notes'         => ['rules' => 'permit_empty|max_length[1000]'],
        ];

        if (! $this->validateData($data, $rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if ($data['schedule_date'] < date('Y-m-d', strtotime('+1 day'))) {
            return redirect()->back()->withInput()->with('error', 'Please choose a date from tomorrow onwards.');
        }

        $service = (new ServiceModel())->find($data['service_id']);
        if ((int) $service['is_available'] !== 1) {
            return redirect()->back()->withInput()->with('error', 'That service is not available right now.');
        }

        (new ScheduleModel())->insert([
            'customer_id'   => $this->userId(),
            'service_id'    => $data['service_id'],
            'schedule_date' => $data['schedule_date'],
            'schedule_time' => $data['schedule_time'],
            'address'       => trim($data['address']),
            'notes'         => trim((string) $data['notes']),
            'status'        => 'pending',
        ]);

        return redirect()->to(site_url('customer/schedules'))
            ->with('success', 'Your ' . $service['name'] . ' request was sent. We will notify you once it is approved.');
    }

    // POST customer/schedules/{id}/reschedule
    public function reschedule(int $id)
    {
        $schedule = $this->findMine($id);
        if ($schedule === null || ! in_array($schedule['status'], ['pending', 'approved'], true)) {
            return redirect()->to(site_url('customer/schedules'))->with('error', 'That booking can no longer be changed.');
        }

        $data  = $this->request->getPost(['schedule_date', 'schedule_time']);
        $rules = [
            'schedule_date' => ['rules' => 'required|valid_date[Y-m-d]', 'errors' => ['required' => 'Please choose a new date.']],
            'schedule_time' => ['rules' => 'required|in_list[' . implode(',', array_keys(config('SunSon')->timeSlots)) . ']', 'errors' => ['required' => 'Please choose a new time.']],
        ];

        if (! $this->validateData($data, $rules) || $data['schedule_date'] < date('Y-m-d', strtotime('+1 day'))) {
            return redirect()->to(site_url('customer/schedules'))->with('error', 'Please choose a valid new date (from tomorrow onwards) and time.');
        }

        // A changed booking goes back to "pending" so staff can approve the new time.
        (new ScheduleModel())->update($id, [
            'schedule_date' => $data['schedule_date'],
            'schedule_time' => $data['schedule_time'],
            'status'        => 'pending',
        ]);

        return redirect()->to(site_url('customer/schedules'))->with('success', 'Your reschedule request was sent for approval.');
    }

    // POST customer/schedules/{id}/cancel
    public function cancel(int $id)
    {
        $schedule = $this->findMine($id);
        if ($schedule === null || ! in_array($schedule['status'], ['pending', 'approved'], true)) {
            return redirect()->to(site_url('customer/schedules'))->with('error', 'That booking can no longer be cancelled.');
        }

        (new ScheduleModel())->update($id, ['status' => 'cancelled']);

        return redirect()->to(site_url('customer/schedules'))->with('success', 'Your booking was cancelled.');
    }

    // A customer may only touch their own bookings.
    private function findMine(int $id): ?array
    {
        return (new ScheduleModel())->where('customer_id', $this->userId())->find($id);
    }
}
