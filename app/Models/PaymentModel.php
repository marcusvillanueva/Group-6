<?php

namespace App\Models;

use CodeIgniter\Model;

// Payments recorded by staff; each one is a receipt in the customer's History tab.
class PaymentModel extends Model
{
    protected $table         = 'payments';
    protected $primaryKey    = 'payment_id';
    protected $returnType    = 'array';
    protected $allowedFields = ['schedule_id', 'customer_id', 'amount', 'method', 'receipt_no', 'recorded_by', 'paid_at'];
}
