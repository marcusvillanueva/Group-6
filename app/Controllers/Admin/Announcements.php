<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AnnouncementModel;

// Admin/HR post reminders, safety guidelines, or urgent notices for employees.
class Announcements extends BaseController
{
    public function index()
    {
        return view('admin/announcements', [
            'title'         => 'Announcements | Sun Son Solar',
            'active'        => 'announcements',
            'announcements' => (new AnnouncementModel())->orderBy('created_at', 'DESC')->findAll(),
        ]);
    }

    public function create()
    {
        $data  = $this->request->getPost(['title', 'category', 'message']);
        $rules = [
            'title'    => ['rules' => 'required|max_length[150]', 'errors' => ['required' => 'Please enter a title.']],
            'category' => ['rules' => 'required|in_list[' . implode(',', config('SunSon')->announcementCategories) . ']'],
            'message'  => ['rules' => 'required|max_length[3000]', 'errors' => ['required' => 'Please enter the announcement.']],
        ];

        if (! $this->validateData($data, $rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data['posted_by'] = session('name') . ' (' . role_label(session('role')) . ')';
        (new AnnouncementModel())->insert($data);

        return redirect()->to(site_url('admin/announcements'))->with('success', 'Announcement posted. Employees will see it on their dashboard.');
    }

    public function delete(int $id)
    {
        (new AnnouncementModel())->delete($id);

        return redirect()->to(site_url('admin/announcements'))->with('success', 'Announcement removed.');
    }
}
