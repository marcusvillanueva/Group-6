<?php

namespace App\Models;

use CodeIgniter\Model;

// Free quote requests sent from the homepage.
class QuoteRequestModel extends Model
{
    protected $table         = 'quote_requests';
    protected $primaryKey    = 'quote_id';
    protected $returnType    = 'array';
    protected $allowedFields = ['fullname', 'email', 'phone', 'address', 'service_interest', 'monthly_bill', 'message', 'status'];

    // created_at is filled in automatically.
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}
