<?php

namespace App\Controllers;

use App\Models\QuoteRequestModel;
use App\Models\ServiceModel;

// "Request a free quote" - works without an account.
class Quote extends BaseController
{
    public function index()
    {
        return view('quote/index', [
            'title'    => 'Request a Free Quote | Sun Son Solar',
            'services' => (new ServiceModel())->where('is_available', 1)->findAll(),
        ]);
    }

    public function submit()
    {
        $data  = $this->request->getPost(['fullname', 'email', 'phone', 'address', 'service_interest', 'monthly_bill', 'message']);
        $data  = array_map(static fn ($value) => trim((string) $value), $data);
        $rules = [
            'fullname'         => ['rules' => 'required|max_length[100]', 'errors' => ['required' => 'Please enter your name.']],
            'email'            => ['rules' => 'required|valid_email', 'errors' => ['required' => 'Email address is required.', 'valid_email' => 'Please enter a valid email address.']],
            'phone'            => ['rules' => 'required|max_length[30]', 'errors' => ['required' => 'Phone number is required.']],
            'address'          => ['rules' => 'required|max_length[255]', 'errors' => ['required' => 'Address is required.']],
            'service_interest' => ['rules' => 'required|max_length[100]', 'errors' => ['required' => 'Please choose what you are interested in.']],
            'monthly_bill'     => ['rules' => 'permit_empty|decimal', 'errors' => ['decimal' => 'Monthly bill must be a number, e.g. 3500.']],
            'message'          => ['rules' => 'permit_empty|max_length[2000]'],
        ];

        if (! $this->validateData($data, $rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data['monthly_bill'] = $data['monthly_bill'] === '' ? null : $data['monthly_bill'];
        $data['status']       = 'new';
        (new QuoteRequestModel())->insert($data);

        return redirect()->to(site_url('quote'))
            ->with('success', 'Thank you! Your quote request was sent. Our team will contact you within 1-2 business days.');
    }
}
