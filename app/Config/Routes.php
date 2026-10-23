<?php

use CodeIgniter\Router\RouteCollection;

/**
 * Sun Son Solar - every page of the site is listed here.
 *
 * Old plain-PHP file         ->  New CodeIgniter page
 * index.php                  ->  /                      (Home::index)
 * register_customer.php      ->  /register              (Auth::register)
 * login.php                  ->  /login (form POST)     (Auth::login)
 * logout.php                 ->  /logout                (Auth::logout)
 * customer_dashboard.php     ->  /customer/dashboard
 * employee_dashboard.php     ->  /employee/dashboard
 * admin_dashboard.php        ->  /admin/users/create    (the "create employee" form)
 *
 * @var RouteCollection $routes
 */

// ---------------------------------------------------------------
// Public pages (no login needed)
// ---------------------------------------------------------------
$routes->get('/', 'Home::index');
$routes->get('legal', 'Home::legal');
$routes->get('quote', 'Quote::index');
$routes->post('quote', 'Quote::submit');

$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::saveCustomer');
$routes->get('login', 'Auth::loginPage');
$routes->post('login', 'Auth::login');
$routes->get('logout', 'Auth::logout');

// ---------------------------------------------------------------
// Customer area - customers only
// ---------------------------------------------------------------
$routes->group('customer', ['filter' => 'auth:customer'], static function ($routes) {
    $routes->get('dashboard', 'Customer\Dashboard::index');

    $routes->get('schedules', 'Customer\Schedules::index');
    $routes->post('schedules', 'Customer\Schedules::book');
    $routes->post('schedules/(:num)/reschedule', 'Customer\Schedules::reschedule/$1');
    $routes->post('schedules/(:num)/cancel', 'Customer\Schedules::cancel/$1');

    $routes->get('notifications', 'Customer\Notifications::index');

    $routes->get('history', 'Customer\History::index');
    $routes->get('receipt/(:num)', 'Customer\History::receipt/$1');

    $routes->get('support', 'Customer\Support::index');
    $routes->post('support', 'Customer\Support::create');
    $routes->get('support/(:num)', 'Customer\Support::show/$1');
    $routes->post('support/(:num)', 'Customer\Support::reply/$1');

    $routes->get('profile', 'Customer\Profile::index');
    $routes->post('profile', 'Customer\Profile::update');
    $routes->post('profile/password', 'Customer\Profile::changePassword');
});

// ---------------------------------------------------------------
// Employee area - technicians and other field staff
// ---------------------------------------------------------------
$routes->group('employee', ['filter' => 'auth:employee,technician,dispatcher'], static function ($routes) {
    $routes->get('dashboard', 'Employee\Dashboard::index');
    $routes->post('time-in', 'Employee\Dashboard::timeIn');
    $routes->post('report', 'Employee\Dashboard::report');
    $routes->get('jobs/(:num)', 'Employee\Jobs::show/$1');
    $routes->post('jobs/(:num)/complete', 'Employee\Jobs::complete/$1');
});

// ---------------------------------------------------------------
// Staff area - Admin, HR and Dispatcher (each page has its own roles,
// see $staffPermissions in app/Config/SunSon.php)
// ---------------------------------------------------------------
$routes->group('admin', static function ($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index', ['filter' => 'auth:admin,hr,dispatcher']);

    $routes->group('users', ['filter' => 'auth:admin,hr'], static function ($routes) {
        $routes->get('/', 'Admin\Users::index');
        $routes->get('create', 'Admin\Users::create');
        $routes->post('create', 'Admin\Users::store');
        $routes->get('(:segment)/(:num)/edit', 'Admin\Users::edit/$1/$2');
        $routes->post('(:segment)/(:num)/edit', 'Admin\Users::update/$1/$2');
        $routes->post('(:segment)/(:num)/toggle', 'Admin\Users::toggle/$1/$2');
    });

    $routes->group('schedules', ['filter' => 'auth:admin,dispatcher'], static function ($routes) {
        $routes->get('/', 'Admin\Schedules::index');
        $routes->get('(:num)', 'Admin\Schedules::manage/$1');
        $routes->post('(:num)', 'Admin\Schedules::update/$1');
        $routes->post('(:num)/payment', 'Admin\Schedules::recordPayment/$1');
    });

    $routes->get('reports', 'Admin\Reports::index', ['filter' => 'auth:admin']);

    $routes->group('notifications', ['filter' => 'auth:admin'], static function ($routes) {
        $routes->get('/', 'Admin\Notifications::index');
        $routes->post('send', 'Admin\Notifications::send');
        $routes->post('reminders', 'Admin\Notifications::sendReminders');
        $routes->post('settings', 'Admin\Notifications::saveSettings');
    });

    $routes->group('settings', ['filter' => 'auth:admin'], static function ($routes) {
        $routes->get('/', 'Admin\Settings::index');
        $routes->post('(:segment)/save', 'Admin\Settings::save/$1');
    });

    $routes->group('announcements', ['filter' => 'auth:admin,hr'], static function ($routes) {
        $routes->get('/', 'Admin\Announcements::index');
        $routes->post('/', 'Admin\Announcements::create');
        $routes->post('(:num)/delete', 'Admin\Announcements::delete/$1');
    });

    $routes->get('attendance', 'Admin\Attendance::index', ['filter' => 'auth:admin,hr,dispatcher']);

    $routes->group('issues', ['filter' => 'auth:admin,dispatcher'], static function ($routes) {
        $routes->get('/', 'Admin\Issues::index');
        $routes->post('(:num)/resolve', 'Admin\Issues::resolve/$1');
    });

    $routes->group('support', ['filter' => 'auth:admin'], static function ($routes) {
        $routes->get('/', 'Admin\Support::index');
        $routes->get('(:num)', 'Admin\Support::show/$1');
        $routes->post('(:num)', 'Admin\Support::reply/$1');
        $routes->post('(:num)/close', 'Admin\Support::close/$1');
    });

    $routes->group('quotes', ['filter' => 'auth:admin,dispatcher'], static function ($routes) {
        $routes->get('/', 'Admin\Quotes::index');
        $routes->post('(:num)/contacted', 'Admin\Quotes::markContacted/$1');
    });
});
