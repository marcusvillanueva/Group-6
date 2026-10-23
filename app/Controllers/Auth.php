<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\EmployeeModel;

// Was: register_customer.php, login.php and logout.php
class Auth extends BaseController
{
    // GET /register - the customer sign-up form
    public function register()
    {
        return view('auth/register_customer', ['title' => 'Customer Sign Up | Sun Son Solar']);
    }

    // POST /register - save a new customer (customers sign up on their own)
    public function saveCustomer()
    {
        $data = $this->request->getPost([
            'firstname', 'lastname', 'middlename', 'birthdate', 'gender',
            'email', 'phone', 'address', 'username', 'password',
        ]);
        $data = array_map(static fn ($value) => trim((string) $value), $data);

        // Rules are in app/Config/Validation.php ($customerSignup).
        if (! $this->validateData($data, 'customerSignup')) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

        (new CustomerModel())->insert($data);

        return redirect()->to(site_url('/') . '#login')
            ->with('success', 'Your customer account was created. You can now log in below.');
    }

    // GET /login - the login forms live on the homepage
    public function loginPage()
    {
        return redirect()->to(site_url('/') . '#login');
    }

    // POST /login - check the username and password
    public function login()
    {
        $accountType = trim((string) $this->request->getPost('account_type'));
        $username    = trim((string) $this->request->getPost('username'));
        $password    = (string) $this->request->getPost('password');

        if (! in_array($accountType, ['customer', 'employee'], true) || $username === '' || $password === '') {
            return $this->loginFailed('Please enter a username and password in the correct login section.');
        }

        $model = $accountType === 'customer' ? new CustomerModel() : new EmployeeModel();
        $user  = $model->where('username', $username)->first();

        if ($user === null || ! password_verify($password, $user['password'])) {
            return $this->loginFailed('The username or password is incorrect, or the account type does not match.');
        }

        if ((int) $user['is_active'] !== 1) {
            return $this->loginFailed('This account has been deactivated. Please contact Sun Son Solar.');
        }

        // Same session values as the old login.php
        $session = session();
        $session->regenerate(true);
        $session->set([
            'user_id'      => $accountType === 'customer' ? $user['customer_id'] : $user['employee_id'],
            'username'     => $user['username'],
            'name'         => trim($user['firstname'] . ' ' . $user['lastname']),
            'email'        => $user['email'],
            'account_type' => $accountType,
            'role'         => $accountType === 'customer' ? 'customer' : $user['role'],
            'department'   => $user['department'] ?? null,
        ]);

        return redirect()->to(dashboard_url());
    }

    // GET /logout
    public function logout()
    {
        session()->destroy();

        return redirect()->to(site_url('/'));
    }

    private function loginFailed(string $message)
    {
        return redirect()->to(site_url('/') . '#login')->with('login_error', $message);
    }
}
