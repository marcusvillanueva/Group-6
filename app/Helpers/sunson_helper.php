<?php

/**
 * Small helper functions used by the Sun Son Solar views and controllers.
 * Loaded automatically (see app/Config/Autoload.php -> $helpers).
 */

// Is anyone logged in right now?
function is_logged_in(): bool
{
    return session()->get('account_type') !== null;
}

// Is the logged-in person an employee with one of these roles?
function has_role(array $roles): bool
{
    return session()->get('account_type') === 'employee'
        && in_array(session()->get('role'), $roles, true);
}

// May the logged-in employee open this part of the staff area?
function staff_can(string $section): bool
{
    $allowed = config('SunSon')->staffPermissions[$section] ?? [];

    return has_role($allowed);
}

// The page each kind of account lands on after logging in.
function dashboard_url(): string
{
    if (session()->get('account_type') === 'customer') {
        return site_url('customer/dashboard');
    }

    if (has_role(['admin', 'hr', 'dispatcher'])) {
        return site_url('admin/dashboard');
    }

    if (session()->get('account_type') === 'employee') {
        return site_url('employee/dashboard');
    }

    return site_url('/') . '#login';
}

// "admin" -> "Admin", "customer" -> "Customer", etc.
function role_label(?string $role): string
{
    if ($role === 'customer') {
        return 'Customer';
    }

    return config('SunSon')->roles[$role] ?? ucfirst((string) $role);
}

// 1500 -> "₱1,500.00". Empty price -> "Price on request".
function peso($amount): string
{
    if ($amount === null || $amount === '') {
        return 'Price on request';
    }

    return '₱' . number_format((float) $amount, 2);
}

// Colored label for a status such as pending, approved, open, resolved.
function status_badge(?string $status): string
{
    $status = (string) $status;

    return '<span class="badge badge-' . esc($status, 'attr') . '">' . esc(ucfirst($status)) . '</span>';
}

function nice_date(?string $date): string
{
    return $date ? date('M j, Y', strtotime($date)) : '';
}

function nice_time(?string $time): string
{
    return $time ? date('g:i A', strtotime($time)) : '';
}

function nice_datetime(?string $datetime): string
{
    return $datetime ? date('M j, Y g:i A', strtotime($datetime)) : '';
}
