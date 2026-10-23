<?php

namespace App\Models;

use CodeIgniter\Model;

// Problems reported by employees from the field.
class IssueReportModel extends Model
{
    protected $table         = 'issue_reports';
    protected $primaryKey    = 'report_id';
    protected $returnType    = 'array';
    protected $allowedFields = ['employee_id', 'employee_name', 'schedule_id', 'category', 'description', 'status'];

    // created_at is filled in automatically.
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}
