<?php

namespace App\Models;

use CodeIgniter\Model;

// One row per "Time in at site" button press.
class AttendanceModel extends Model
{
    protected $table         = 'attendance';
    protected $primaryKey    = 'attendance_id';
    protected $returnType    = 'array';
    protected $allowedFields = ['employee_id', 'employee_name', 'latitude', 'longitude', 'time_in'];
}
