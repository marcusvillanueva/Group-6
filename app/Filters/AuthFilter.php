<?php

namespace App\Filters;

use App\Models\CustomerModel;
use App\Models\EmployeeModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Replaces the "if (!isset($_SESSION[...])) header('Location: ...')" checks
 * that used to be at the top of every dashboard file.
 *
 * Used in app/Config/Routes.php like this:
 *   'auth:customer'          -> customers only
 *   'auth:admin,hr'          -> employees whose role is admin or hr
 *   'auth:employee-any'      -> any logged-in employee
 */
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $type    = $session->get('account_type');

        if ($type === null) {
            return redirect()->to(site_url('/') . '#login')
                ->with('login_error', 'Please log in first.');
        }

        // If an Admin deactivated this account, end the session right away.
        if (! $this->accountIsStillActive($type, (int) $session->get('user_id'))) {
            $session->destroy();

            return redirect()->to(site_url('/') . '#login')
                ->with('login_error', 'This account has been deactivated. Please contact Sun Son Solar.');
        }

        $allowed = $arguments ?? [];

        $ok = ($type === 'customer' && in_array('customer', $allowed, true))
            || ($type === 'employee' && in_array('employee-any', $allowed, true))
            || ($type === 'employee' && in_array($session->get('role'), $allowed, true));

        if (! $ok) {
            // Logged in, but this page is for a different kind of account.
            return redirect()->to(dashboard_url())
                ->with('error', 'You do not have access to that page.');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }

    private function accountIsStillActive(string $type, int $id): bool
    {
        $model = $type === 'customer' ? new CustomerModel() : new EmployeeModel();
        $user  = $model->find($id);

        return $user !== null && (int) $user['is_active'] === 1;
    }
}
