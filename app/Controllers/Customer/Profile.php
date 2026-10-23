<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\CustomerModel;

// Profile section: personal information, preferences and password.
class Profile extends BaseController
{
    public function index()
    {
        return view('customer/profile', [
            'title'  => 'My Profile | Sun Son Solar',
            'active' => 'profile',
            'person' => (new CustomerModel())->find($this->userId()),
        ]);
    }

    // POST customer/profile - personal details + preferences
    public function update()
    {
        $data = $this->request->getPost(['firstname', 'lastname', 'middlename', 'birthdate', 'gender', 'email', 'phone', 'address']);
        $data = array_map(static fn ($value) => trim((string) $value), $data);

        if (! $this->validateData($data, 'personUpdate')) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Checkboxes are only sent when ticked.
        $data['receive_reminders']  = $this->request->getPost('receive_reminders') ? 1 : 0;
        $data['receive_promotions'] = $this->request->getPost('receive_promotions') ? 1 : 0;

        (new CustomerModel())->update($this->userId(), $data);

        // Keep the name in the header up to date.
        session()->set([
            'name'  => $data['firstname'] . ' ' . $data['lastname'],
            'email' => $data['email'],
        ]);

        return redirect()->to(site_url('customer/profile'))->with('success', 'Your profile was updated.');
    }

    // POST customer/profile/password
    public function changePassword()
    {
        $data = $this->request->getPost(['current_password', 'new_password', 'confirm_password']);

        if (! $this->validateData($data, 'passwordChange')) {
            return redirect()->to(site_url('customer/profile') . '#password')->with('errors', $this->validator->getErrors());
        }

        $model    = new CustomerModel();
        $customer = $model->find($this->userId());

        if (! password_verify($data['current_password'], $customer['password'])) {
            return redirect()->to(site_url('customer/profile') . '#password')->with('error', 'Your current password is incorrect.');
        }

        $model->update($this->userId(), ['password' => password_hash($data['new_password'], PASSWORD_DEFAULT)]);

        return redirect()->to(site_url('customer/profile'))->with('success', 'Your password was changed.');
    }
}
