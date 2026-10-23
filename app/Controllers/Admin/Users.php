<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CustomerModel;
use App\Models\EmployeeModel;

// User management: add employees, view, edit, deactivate accounts.
// The "create employee" part was: admin_dashboard.php
class Users extends BaseController
{
    // GET admin/users?type=employees|customers&q=search
    public function index()
    {
        // HR manages employees only; customers are managed by an Admin.
        $type = $this->request->getGet('type') === 'customers' && session('role') === 'admin' ? 'customers' : 'employees';
        $q    = trim((string) $this->request->getGet('q'));

        $model = $type === 'customers' ? new CustomerModel() : new EmployeeModel();
        if ($q !== '') {
            $model->groupStart()
                ->like('firstname', $q)->orLike('lastname', $q)->orLike('username', $q)->orLike('email', $q)
                ->groupEnd();
        }

        return view('admin/users', [
            'title'  => 'User Management | Sun Son Solar',
            'active' => 'users',
            'type'   => $type,
            'q'      => $q,
            'users'  => $model->orderBy('is_active', 'DESC')->orderBy('lastname')->findAll(),
        ]);
    }

    // GET admin/users/create - same form as the old admin_dashboard.php
    public function create()
    {
        return view('admin/user_create', [
            'title'        => 'HR / Admin | Sun Son Solar',
            'active'       => 'users',
            'allowedRoles' => $this->allowedRoles(),
        ]);
    }

    // POST admin/users/create
    public function store()
    {
        $data = $this->request->getPost([
            'firstname', 'lastname', 'middlename', 'birthdate', 'gender', 'email', 'phone', 'address',
            'username', 'password', 'department', 'role',
        ]);
        $data = array_map(static fn ($value) => trim((string) $value), $data);

        if (! $this->validateData($data, 'employeeCreate')) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Only an Admin may create HR or Admin accounts. HR can create the other roles.
        if (! array_key_exists($data['role'], $this->allowedRoles())) {
            $data['role'] = 'employee';
        }

        $data['password']  = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        $data['is_active'] = 1;
        (new EmployeeModel())->insert($data);

        return redirect()->to(site_url('admin/users/create'))
            ->with('success', 'The new ' . role_label($data['role']) . ' account for ' . $data['firstname'] . ' has been created.');
    }

    // GET admin/users/{employees|customers}/{id}/edit
    public function edit(string $type, int $id)
    {
        $user = $this->findManageable($type, $id);
        if ($user === null) {
            return redirect()->to(site_url('admin/users'))->with('error', 'You cannot edit that account.');
        }

        return view('admin/user_edit', [
            'title'        => 'Edit User | Sun Son Solar',
            'active'       => 'users',
            'type'         => $type,
            'id'           => $id,
            'person'       => $user,
            'allowedRoles' => $this->allowedRoles(),
        ]);
    }

    // POST admin/users/{type}/{id}/edit
    public function update(string $type, int $id)
    {
        $user = $this->findManageable($type, $id);
        if ($user === null) {
            return redirect()->to(site_url('admin/users'))->with('error', 'You cannot edit that account.');
        }

        $data = $this->request->getPost(['firstname', 'lastname', 'middlename', 'birthdate', 'gender', 'email', 'phone', 'address']);
        $data = array_map(static fn ($value) => trim((string) $value), $data);

        $rules = config('Validation')->personUpdate;
        if ($type === 'employees') {
            $data['department'] = trim((string) $this->request->getPost('department'));
            $rules['department'] = ['rules' => 'required', 'errors' => ['required' => 'Department is required.']];
        }
        $newPassword = (string) $this->request->getPost('new_password');
        if ($newPassword !== '') {
            $data['new_password']  = $newPassword;
            $rules['new_password'] = ['rules' => 'min_length[8]', 'errors' => ['min_length' => 'Password must be at least 8 characters.']];
        }

        if (! $this->validateData($data, $rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        unset($data['new_password']);
        if ($newPassword !== '') {
            $data['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        // Role changes: only to roles this person is allowed to give, and never your own role.
        $role = (string) $this->request->getPost('role');
        if ($type === 'employees' && $id !== $this->userId() && array_key_exists($role, $this->allowedRoles())) {
            $data['role'] = $role;
        }

        $this->modelFor($type)->update($id, $data);

        if ($type === 'employees' && $id === $this->userId()) {
            session()->set('name', $data['firstname'] . ' ' . $data['lastname']);
        }

        return redirect()->to(site_url('admin/users?type=' . $type))->with('success', 'Account updated.');
    }

    // POST admin/users/{type}/{id}/toggle - deactivate or reactivate (accounts are never deleted)
    public function toggle(string $type, int $id)
    {
        $user = $this->findManageable($type, $id);
        if ($user === null || ($type === 'employees' && $id === $this->userId())) {
            return redirect()->back()->with('error', 'You cannot change the status of that account.');
        }

        $newStatus = (int) $user['is_active'] === 1 ? 0 : 1;
        $this->modelFor($type)->update($id, ['is_active' => $newStatus]);

        return redirect()->to(site_url('admin/users?type=' . $type))
            ->with('success', $user['firstname'] . ' ' . $user['lastname'] . ($newStatus ? ' was reactivated.' : ' was deactivated and can no longer log in.'));
    }

    // Roles the logged-in person may give to others.
    private function allowedRoles(): array
    {
        $roles = config('SunSon')->roles;
        if (session('role') !== 'admin') {
            unset($roles['hr'], $roles['admin']);
        }

        return $roles;
    }

    private function modelFor(string $type)
    {
        return $type === 'customers' ? new CustomerModel() : new EmployeeModel();
    }

    // Returns the user only if the logged-in person may manage them.
    private function findManageable(string $type, int $id): ?array
    {
        if (! in_array($type, ['employees', 'customers'], true)) {
            return null;
        }
        if ($type === 'customers' && session('role') !== 'admin') {
            return null;
        }

        $user = $this->modelFor($type)->find($id);
        if ($user === null) {
            return null;
        }

        // HR may not edit Admin or other HR accounts (except their own details).
        if ($type === 'employees' && session('role') === 'hr' && in_array($user['role'], ['admin', 'hr'], true) && $id !== $this->userId()) {
            return null;
        }

        return $user;
    }
}
