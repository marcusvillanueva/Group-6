<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CustomerModel;
use App\Models\NotificationModel;
use App\Models\ScheduleModel;
use App\Models\SettingModel;

// Notifications control panel: the Admin decides what alerts go out to customers.
class Notifications extends BaseController
{
    public function index()
    {
        $tomorrow = date('Y-m-d', strtotime('+1 day'));

        return view('admin/notifications', [
            'title'        => 'Notifications | Sun Son Solar',
            'active'       => 'notifications',
            'customers'    => (new CustomerModel())->where('is_active', 1)->orderBy('lastname')->findAll(),
            'settings'     => new SettingModel(),
            'tomorrowJobs' => (new ScheduleModel())->where('status', 'approved')->where('schedule_date', $tomorrow)->countAllResults(),
            'recent'       => (new NotificationModel())
                ->select("notifications.*, CONCAT(customers.firstname, ' ', customers.lastname) AS customer_name", false)
                ->join('customers', 'customers.customer_id = notifications.customer_id')
                ->orderBy('notifications.created_at', 'DESC')
                ->findAll(15),
        ]);
    }

    // POST admin/notifications/send - write a reminder, promotion or system update
    public function send()
    {
        $data  = $this->request->getPost(['type', 'audience', 'customer_id', 'title', 'message']);
        $rules = [
            'type'     => ['rules' => 'required|in_list[' . implode(',', array_keys(config('SunSon')->notificationTypes)) . ']'],
            'audience' => ['rules' => 'required|in_list[all,one]'],
            'title'    => ['rules' => 'required|max_length[150]', 'errors' => ['required' => 'Please enter a title.']],
            'message'  => ['rules' => 'required|max_length[2000]', 'errors' => ['required' => 'Please enter a message.']],
        ];
        if ($data['audience'] === 'one') {
            $rules['customer_id'] = ['rules' => 'required|is_not_unique[customers.customer_id]', 'errors' => ['required' => 'Please choose a customer.']];
        }

        if (! $this->validateData($data, $rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $customers = new CustomerModel();
        $customers->where('is_active', 1);
        if ($data['audience'] === 'one') {
            $customers->where('customer_id', (int) $data['customer_id']);
        }
        // Respect each customer's preferences.
        if ($data['type'] === 'promotion') {
            $customers->where('receive_promotions', 1);
        }
        if ($data['type'] === 'reminder') {
            $customers->where('receive_reminders', 1);
        }

        $notifications = new NotificationModel();
        $sent          = 0;
        foreach ($customers->findAll() as $customer) {
            $notifications->send((int) $customer['customer_id'], $data['type'], trim($data['title']), trim($data['message']));
            $sent++;
        }

        return redirect()->to(site_url('admin/notifications'))
            ->with('success', "Notification sent to {$sent} customer(s). Customers who turned this type off in their preferences were skipped.");
    }

    // POST admin/notifications/reminders - remind customers about tomorrow's bookings
    public function sendReminders()
    {
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        $bookings = (new ScheduleModel())->withDetails()
            ->where('schedules.status', 'approved')
            ->where('schedules.schedule_date', $tomorrow)
            ->where('customers.receive_reminders', 1)
            ->findAll();

        $notifications = new NotificationModel();
        $sent          = 0;
        foreach ($bookings as $booking) {
            $title = 'Reminder: booking #' . $booking['schedule_id'] . ' is tomorrow';

            // Don't send the same reminder twice.
            if ($notifications->where('customer_id', $booking['customer_id'])->where('title', $title)->countAllResults() > 0) {
                continue;
            }

            $notifications->send(
                (int) $booking['customer_id'],
                'reminder',
                $title,
                'Your ' . $booking['service_name'] . ' is scheduled for ' . nice_date($booking['schedule_date']) . ' at ' . nice_time($booking['schedule_time'])
                . ($booking['technician_name'] ? ' with ' . $booking['technician_name'] : '') . '. Please make sure someone can let our team in.'
            );
            $sent++;
        }

        return redirect()->to(site_url('admin/notifications'))->with('success', "{$sent} reminder(s) sent for tomorrow's bookings.");
    }

    // POST admin/notifications/settings - automatic alerts on/off
    public function saveSettings()
    {
        $settings = new SettingModel();
        $settings->setValue('auto_schedule_updates', $this->request->getPost('auto_schedule_updates') ? '1' : '0');
        $settings->setValue('auto_payment_receipts', $this->request->getPost('auto_payment_receipts') ? '1' : '0');

        return redirect()->to(site_url('admin/notifications'))->with('success', 'Automatic notification settings saved.');
    }
}
