<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\CustomerModel;
use App\Models\PaymentModel;
use App\Models\ScheduleModel;

// Completed services, payments and receipts.
class History extends BaseController
{
    public function index()
    {
        $customerId = $this->userId();

        $payments = (new PaymentModel())
            ->select('payments.*, services.name AS service_name, schedules.schedule_date')
            ->join('schedules', 'schedules.schedule_id = payments.schedule_id')
            ->join('services', 'services.service_id = schedules.service_id')
            ->where('payments.customer_id', $customerId)
            ->orderBy('payments.paid_at', 'DESC')
            ->findAll();

        $data = [
            'title'     => 'History | Sun Son Solar',
            'active'    => 'history',
            'completed' => (new ScheduleModel())->withDetails()
                ->where('schedules.customer_id', $customerId)
                ->where('schedules.status', 'completed')
                ->orderBy('schedules.schedule_date', 'DESC')
                ->findAll(),
            'payments'  => $payments,
            'totalPaid' => array_sum(array_column($payments, 'amount')),
        ];

        return view('customer/history', $data);
    }

    // GET customer/receipt/{payment_id} - printable receipt
    public function receipt(int $paymentId)
    {
        $payment = (new PaymentModel())
            ->select('payments.*, services.name AS service_name, schedules.schedule_date, schedules.schedule_time, schedules.address')
            ->join('schedules', 'schedules.schedule_id = payments.schedule_id')
            ->join('services', 'services.service_id = schedules.service_id')
            ->where('payments.customer_id', $this->userId())
            ->where('payments.payment_id', $paymentId)
            ->first();

        if ($payment === null) {
            return redirect()->to(site_url('customer/history'))->with('error', 'Receipt not found.');
        }

        return view('customer/receipt', [
            'title'    => 'Receipt ' . $payment['receipt_no'] . ' | Sun Son Solar',
            'payment'  => $payment,
            'customer' => (new CustomerModel())->find($this->userId()),
        ]);
    }
}
