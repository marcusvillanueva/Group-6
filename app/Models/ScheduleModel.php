<?php

namespace App\Models;

use CodeIgniter\Model;

// Customer bookings for services (consultation, installation, maintenance...).
class ScheduleModel extends Model
{
    protected $table         = 'schedules';
    protected $primaryKey    = 'schedule_id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'customer_id', 'service_id', 'schedule_date', 'schedule_time', 'address', 'notes',
        'status', 'technician_id', 'admin_note',
    ];

    // created_at and updated_at are filled in automatically.
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Start a query that also brings back the service name, customer name,
     * technician name and receipt number, so views don't need extra queries.
     * Example: $model->withDetails()->where('schedules.customer_id', 5)->findAll();
     */
    public function withDetails()
    {
        return $this->select(
            "schedules.*, services.name AS service_name, services.price AS service_price,
            CONCAT(customers.firstname, ' ', customers.lastname) AS customer_name,
            CONCAT(employees.firstname, ' ', employees.lastname) AS technician_name,
            payments.payment_id, payments.receipt_no, payments.amount AS paid_amount",
            false
        )
            ->join('services', 'services.service_id = schedules.service_id')
            ->join('customers', 'customers.customer_id = schedules.customer_id')
            ->join('employees', 'employees.employee_id = schedules.technician_id', 'left')
            ->join('payments', 'payments.schedule_id = schedules.schedule_id', 'left');
    }
}
