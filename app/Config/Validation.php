<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------
    // --------------------------------------------------------------------
    // Sun Son Solar rule groups
    // Used in controllers like: $this->validateData($data, 'customerSignup')
    // The messages match the ones from the old plain-PHP version.
    // --------------------------------------------------------------------

    public array $customerSignup = [];
    public array $employeeCreate = [];
    public array $personUpdate   = [];

    // Fields every customer and employee has.
    private array $personRules = [
        'firstname' => ['rules' => 'required|max_length[50]', 'errors' => ['required' => 'First name is required.']],
        'lastname'  => ['rules' => 'required|max_length[50]', 'errors' => ['required' => 'Last name is required.']],
        'middlename' => ['rules' => 'permit_empty|max_length[50]'],
        'birthdate' => [
            'rules'  => 'required|valid_date[Y-m-d]',
            'errors' => ['required' => 'Birthdate is required.', 'valid_date' => 'Please enter a valid birthdate.'],
        ],
        'gender' => [
            'rules'  => 'required|in_list[Female,Male,Prefer not to say]',
            'errors' => ['required' => 'Please select a valid gender option.', 'in_list' => 'Please select a valid gender option.'],
        ],
        'email' => [
            'rules'  => 'required|valid_email|max_length[100]',
            'errors' => ['required' => 'Email address is required.', 'valid_email' => 'Please enter a valid email address.'],
        ],
        'phone'   => ['rules' => 'required|max_length[30]', 'errors' => ['required' => 'Phone number is required.']],
        'address' => ['rules' => 'required|max_length[255]', 'errors' => ['required' => 'Address is required.']],
    ];

    // Username and password for a brand-new account.
    private array $accountRules = [
        'username' => [
            // A username may not exist in EITHER table.
            'rules'  => 'required|max_length[50]|is_unique[customers.username]|is_unique[employees.username]',
            'errors' => [
                'required'  => 'Username is required.',
                'is_unique' => 'That username is already in use. Please choose another.',
            ],
        ],
        'password' => [
            'rules'  => 'required|min_length[8]',
            'errors' => ['required' => 'Password is required.', 'min_length' => 'Password must be at least 8 characters.'],
        ],
    ];

    public array $passwordChange = [
        'current_password' => ['rules' => 'required', 'errors' => ['required' => 'Enter your current password.']],
        'new_password'     => [
            'rules'  => 'required|min_length[8]',
            'errors' => ['required' => 'Enter a new password.', 'min_length' => 'Password must be at least 8 characters.'],
        ],
        'confirm_password' => [
            'rules'  => 'required|matches[new_password]',
            'errors' => ['required' => 'Please confirm the new password.', 'matches' => 'The new passwords do not match.'],
        ],
    ];

    public function __construct()
    {
        parent::__construct();

        $department = [
            'department' => ['rules' => 'required', 'errors' => ['required' => 'Department is required.']],
        ];

        $this->customerSignup = $this->personRules + $this->accountRules;
        $this->employeeCreate = $this->personRules + $this->accountRules + $department;
        $this->personUpdate   = $this->personRules;
    }
}
