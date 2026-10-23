<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\IssueReportModel;

// Problems reported by employees from the field (equipment issues, customer concerns...).
class Issues extends BaseController
{
    public function index()
    {
        return view('admin/issues', [
            'title'   => 'Field Reports | Sun Son Solar',
            'active'  => 'issues',
            'reports' => (new IssueReportModel())
                ->select('issue_reports.*, services.name AS service_name')
                ->join('schedules', 'schedules.schedule_id = issue_reports.schedule_id', 'left')
                ->join('services', 'services.service_id = schedules.service_id', 'left')
                ->orderBy("issue_reports.status = 'open'", 'DESC', false)
                ->orderBy('issue_reports.created_at', 'DESC')
                ->findAll(),
        ]);
    }

    public function resolve(int $id)
    {
        (new IssueReportModel())->update($id, ['status' => 'resolved']);

        return redirect()->to(site_url('admin/issues'))->with('success', 'Report #' . $id . ' marked as resolved.');
    }
}
