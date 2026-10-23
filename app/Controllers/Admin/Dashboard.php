<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AttendanceModel;
use App\Models\CustomerModel;
use App\Models\EmployeeModel;
use App\Models\IssueReportModel;
use App\Models\PaymentModel;
use App\Models\QuoteRequestModel;
use App\Models\ScheduleModel;
use App\Models\TicketModel;

// Staff overview: the numbers and items that need attention today.
class Dashboard extends BaseController
{
    public function index()
    {
        $revenue = (new PaymentModel())->selectSum('amount')
            ->where('paid_at >=', date('Y-m-01 00:00:00'))
            ->first();

        $data = [
            'title'  => 'Staff Dashboard | Sun Son Solar',
            'active' => 'dashboard',
            'stats'  => [
                'Customers'          => (new CustomerModel())->countAllResults(),
                'Active employees'   => (new EmployeeModel())->where('is_active', 1)->countAllResults(),
                'Pending bookings'   => (new ScheduleModel())->where('status', 'pending')->countAllResults(),
                "Today's jobs"       => (new ScheduleModel())->where('status', 'approved')->where('schedule_date', date('Y-m-d'))->countAllResults(),
                'Open field reports' => (new IssueReportModel())->where('status', 'open')->countAllResults(),
                'Open tickets'       => (new TicketModel())->where('status', 'open')->countAllResults(),
                'New quote requests' => (new QuoteRequestModel())->where('status', 'new')->countAllResults(),
                'Revenue this month' => peso($revenue['amount'] ?? 0),
            ],
            'pending' => (new ScheduleModel())->withDetails()->where('schedules.status', 'pending')
                ->orderBy('schedules.schedule_date', 'ASC')->findAll(5),
            'issues'  => (new IssueReportModel())->where('status', 'open')->orderBy('created_at', 'DESC')->findAll(5),
            'checkins' => (new AttendanceModel())->orderBy('time_in', 'DESC')->findAll(5),
        ];

        return view('admin/dashboard', $data);
    }
}
