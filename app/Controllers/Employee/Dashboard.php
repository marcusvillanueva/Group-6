<?php

namespace App\Controllers\Employee;

use App\Controllers\BaseController;
use App\Models\AnnouncementModel;
use App\Models\AttendanceModel;
use App\Models\IssueReportModel;
use App\Models\ScheduleModel;

// Was: employee_dashboard.php
// Everything a technician needs is on one page, since some of them
// are not used to new technology: time in, jobs, announcements, reports.
class Dashboard extends BaseController
{
    public function index()
    {
        $employeeId = $this->userId();

        $data = [
            'title'         => 'Employee Account | Sun Son Solar',
            'lastTimeIn'    => (new AttendanceModel())->where('employee_id', $employeeId)
                ->orderBy('time_in', 'DESC')->first(),
            'jobs'          => $this->myJobs($employeeId),
            'announcements' => (new AnnouncementModel())->orderBy('created_at', 'DESC')->findAll(6),
            'reports'       => (new IssueReportModel())->where('employee_id', $employeeId)
                ->orderBy('created_at', 'DESC')->findAll(5),
        ];

        return view('employee/dashboard', $data);
    }

    // POST employee/time-in - the one big button (name, time, coordinates)
    public function timeIn()
    {
        $latitude  = $this->request->getPost('latitude');
        $longitude = $this->request->getPost('longitude');

        // Keep the coordinates only if the browser sent real numbers.
        $hasLocation = is_numeric($latitude) && is_numeric($longitude)
            && abs((float) $latitude) <= 90 && abs((float) $longitude) <= 180;

        (new AttendanceModel())->insert([
            'employee_id'   => $this->userId(),
            'employee_name' => session('name'),
            'latitude'      => $hasLocation ? $latitude : null,
            'longitude'     => $hasLocation ? $longitude : null,
            'time_in'       => date('Y-m-d H:i:s'),
        ]);

        $message = 'Time in recorded at ' . date('g:i A') . '.';
        if (! $hasLocation) {
            $message .= ' Your location could not be read, so please tell your dispatcher where you are.';
        }

        return redirect()->to(site_url('employee/dashboard'))->with('success', $message);
    }

    // POST employee/report - log a problem from the field (goes to the admin dashboard)
    public function report()
    {
        $data  = $this->request->getPost(['schedule_id', 'category', 'description']);
        $rules = [
            'category'    => ['rules' => 'required|in_list[' . implode(',', config('SunSon')->issueCategories) . ']', 'errors' => ['required' => 'Please choose what kind of problem it is.', 'in_list' => 'Please choose what kind of problem it is.']],
            'description' => ['rules' => 'required|max_length[3000]', 'errors' => ['required' => 'Please describe the problem.']],
        ];

        if (! $this->validateData($data, $rules)) {
            return redirect()->to(site_url('employee/dashboard') . '#report')->withInput()->with('errors', $this->validator->getErrors());
        }

        // A related job is optional, but it must be one of this employee's jobs.
        $scheduleId = null;
        if (! empty($data['schedule_id'])) {
            $job        = (new ScheduleModel())->where('technician_id', $this->userId())->find((int) $data['schedule_id']);
            $scheduleId = $job['schedule_id'] ?? null;
        }

        (new IssueReportModel())->insert([
            'employee_id'   => $this->userId(),
            'employee_name' => session('name'),
            'schedule_id'   => $scheduleId,
            'category'      => $data['category'],
            'description'   => trim($data['description']),
            'status'        => 'open',
        ]);

        return redirect()->to(site_url('employee/dashboard') . '#report')->with('success', 'Your report was sent to the admin dashboard.');
    }

    // Approved jobs assigned to this employee, from today onwards.
    private function myJobs(int $employeeId): array
    {
        return (new ScheduleModel())->withDetails()
            ->where('schedules.technician_id', $employeeId)
            ->where('schedules.status', 'approved')
            ->where('schedules.schedule_date >=', date('Y-m-d'))
            ->orderBy('schedules.schedule_date', 'ASC')
            ->orderBy('schedules.schedule_time', 'ASC')
            ->findAll();
    }
}
