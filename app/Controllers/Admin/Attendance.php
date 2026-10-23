<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AttendanceModel;

// Site time-in records (name, time, coordinates) for tracking field attendance.
class Attendance extends BaseController
{
    // GET admin/attendance?date=2026-10-23
    public function index()
    {
        $date  = (string) $this->request->getGet('date');
        $model = new AttendanceModel();

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $model->where('time_in >=', $date . ' 00:00:00')->where('time_in <=', $date . ' 23:59:59');
        } else {
            $date = '';
        }

        return view('admin/attendance', [
            'title'   => 'Attendance | Sun Son Solar',
            'active'  => 'attendance',
            'date'    => $date,
            'records' => $model->orderBy('time_in', 'DESC')->findAll(100),
        ]);
    }
}
