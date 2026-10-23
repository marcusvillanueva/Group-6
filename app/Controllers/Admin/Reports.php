<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

// Reports and analytics: customer activity, service usage and revenue.
// The charts are drawn with plain CSS bars (no chart library needed).
class Reports extends BaseController
{
    public function index()
    {
        $db = db_connect();

        // The last 6 months, oldest first, all starting at 0.
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $months[date('Y-m', strtotime("first day of -{$i} month"))] = 0;
        }
        $start = array_key_first($months) . '-01';

        $newCustomers = $this->perMonth($months, $db->query(
            "SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, COUNT(*) AS total
             FROM customers WHERE created_at >= ? GROUP BY ym",
            [$start]
        )->getResultArray());

        $bookings = $this->perMonth($months, $db->query(
            "SELECT DATE_FORMAT(schedule_date, '%Y-%m') AS ym, COUNT(*) AS total
             FROM schedules WHERE schedule_date >= ? AND status NOT IN ('cancelled', 'declined') GROUP BY ym",
            [$start]
        )->getResultArray());

        $revenue = $this->perMonth($months, $db->query(
            "SELECT DATE_FORMAT(paid_at, '%Y-%m') AS ym, SUM(amount) AS total
             FROM payments WHERE paid_at >= ? GROUP BY ym",
            [$start]
        )->getResultArray());

        // How often each service is booked, and how much it earned.
        $serviceUsage = $db->query(
            "SELECT services.name,
                    COUNT(schedules.schedule_id) AS bookings,
                    COALESCE(SUM(payments.amount), 0) AS revenue
             FROM services
             LEFT JOIN schedules ON schedules.service_id = services.service_id
                  AND schedules.status NOT IN ('cancelled', 'declined')
             LEFT JOIN payments ON payments.schedule_id = schedules.schedule_id
             GROUP BY services.service_id, services.name
             ORDER BY bookings DESC"
        )->getResultArray();

        $statusCounts = $db->query(
            'SELECT status, COUNT(*) AS total FROM schedules GROUP BY status ORDER BY total DESC'
        )->getResultArray();

        // Most active customers.
        $topCustomers = $db->query(
            "SELECT CONCAT(customers.firstname, ' ', customers.lastname) AS name,
                    COUNT(schedules.schedule_id) AS bookings,
                    (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE payments.customer_id = customers.customer_id) AS spent
             FROM customers
             JOIN schedules ON schedules.customer_id = customers.customer_id
             GROUP BY customers.customer_id
             ORDER BY bookings DESC, spent DESC
             LIMIT 5"
        )->getResultArray();

        $totals = $db->query(
            "SELECT
                (SELECT COUNT(*) FROM customers) AS customers,
                (SELECT COUNT(*) FROM schedules) AS bookings,
                (SELECT COUNT(*) FROM schedules WHERE status = 'completed') AS completed,
                (SELECT COALESCE(SUM(amount), 0) FROM payments) AS revenue"
        )->getRowArray();

        return view('admin/reports', [
            'title'        => 'Reports | Sun Son Solar',
            'active'       => 'reports',
            'newCustomers' => $newCustomers,
            'bookings'     => $bookings,
            'revenue'      => $revenue,
            'serviceUsage' => $serviceUsage,
            'statusCounts' => $statusCounts,
            'topCustomers' => $topCustomers,
            'totals'       => $totals,
        ]);
    }

    // Puts query results into the 6-month list (months with no data stay 0).
    private function perMonth(array $months, array $rows): array
    {
        foreach ($rows as $row) {
            if (array_key_exists($row['ym'], $months)) {
                $months[$row['ym']] = (float) $row['total'];
            }
        }

        return $months;
    }
}
