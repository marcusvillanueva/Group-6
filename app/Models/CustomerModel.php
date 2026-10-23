<?php

namespace App\Models;

use CodeIgniter\Model;

// The customers table from Activity 1 (plus the new columns from the upgrade).
class CustomerModel extends Model
{
    protected $table         = 'customers';
    protected $primaryKey    = 'customer_id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'firstname', 'lastname', 'middlename', 'birthdate', 'gender', 'email', 'phone', 'address',
        'username', 'password', 'is_active', 'receive_reminders', 'receive_promotions',
    ];
}
