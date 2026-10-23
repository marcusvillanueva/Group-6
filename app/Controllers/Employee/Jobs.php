<?php

namespace App\Controllers\Employee;

use App\Controllers\BaseController;
use App\Models\NotificationModel;
use App\Models\ScheduleModel;
use App\Models\SettingModel;

// Customer details for a job - view only, and only for jobs assigned to you.
class Jobs extends BaseController
{
    public function show(int $id)
    {
        $job = $this->findMyJob($id);
        if ($job === null) {
            return redirect()->to(site_url('employee/dashboard'))->with('error', 'That job is not assigned to you.');
        }

        return view('employee/job', ['title' => 'Job #' . $id . ' | Sun Son Solar', 'job' => $job]);
    }

    // POST employee/jobs/{id}/complete
    public function complete(int $id)
    {
        $job = $this->findMyJob($id);
        if ($job === null || $job['status'] !== 'approved') {
            return redirect()->to(site_url('employee/dashboard'))->with('error', 'Only approved jobs assigned to you can be completed.');
        }

        (new ScheduleModel())->update($id, ['status' => 'completed']);

        if ((new SettingModel())->isOn('auto_schedule_updates')) {
            (new NotificationModel())->send(
                (int) $job['customer_id'],
                'update',
                'Service completed: ' . $job['service_name'],
                'Your ' . $job['service_name'] . ' (booking #' . $id . ') was completed by ' . session('name') . '. Thank you for choosing Sun Son Solar!'
            );
        }

        return redirect()->to(site_url('employee/dashboard'))->with('success', 'Job #' . $id . ' marked as completed.');
    }

    private function findMyJob(int $id): ?array
    {
        return (new ScheduleModel())->withDetails()
            ->where('schedules.technician_id', $this->userId())
            ->where('schedules.schedule_id', $id)
            ->first();
    }
}
