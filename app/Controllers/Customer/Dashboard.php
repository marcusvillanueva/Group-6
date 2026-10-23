<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\NotificationModel;
use App\Models\ScheduleModel;
use App\Models\TicketModel;

// Was: customer_dashboard.php
class Dashboard extends BaseController
{
    public function index()
    {
        $customerId = $this->userId();

        $data = [
            'title'     => 'Customer Account | Sun Son Solar',
            'active'    => 'dashboard',
            // Next bookings that are still coming up.
            'upcoming'  => (new ScheduleModel())->withDetails()
                ->where('schedules.customer_id', $customerId)
                ->whereIn('schedules.status', ['pending', 'approved'])
                ->where('schedules.schedule_date >=', date('Y-m-d'))
                ->orderBy('schedules.schedule_date', 'ASC')
                ->orderBy('schedules.schedule_time', 'ASC')
                ->findAll(3),
            'latest'    => (new NotificationModel())->where('customer_id', $customerId)
                ->orderBy('created_at', 'DESC')->findAll(3),
            'openTickets' => (new TicketModel())->where('customer_id', $customerId)
                ->where('status !=', 'closed')->countAllResults(),
        ];

        return view('customer/dashboard', $data);
    }
}
