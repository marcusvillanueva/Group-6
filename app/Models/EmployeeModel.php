<?php

namespace App\Models;

use CodeIgniter\Model;

// The employees table from Activity 1 (plus role and is_active).
class EmployeeModel extends Model
{
    protected $table         = 'employees';
    protected $primaryKey    = 'employee_id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'firstname', 'lastname', 'middlename', 'birthdate', 'gender', 'email', 'phone', 'address',
        'username', 'password', 'department', 'role', 'is_active',
    ];

    // Active employees who can be sent to a customer's site.
    public function fieldStaff(): array
    {
        return $this->whereIn('role', ['technician', 'employee'])
            ->where('is_active', 1)
            ->orderBy('firstname')
            ->findAll();
    }
}
