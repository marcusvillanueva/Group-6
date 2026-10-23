# Sun Son Solar — CodeIgniter 4 version

The site is now built on **CodeIgniter 4.7.5** (MVC). The pages, design, database tables and
login rules from Activity 2 are the same; they are just organized into CodeIgniter folders,
and the features from the client interview were added.

---

## 1. Requirements (XAMPP)

| Need | How to check |
|---|---|
| PHP **8.2 or newer** | Open `http://localhost/dashboard/phpinfo.php` and look at the version at the top. XAMPP 8.2.x is fine. |
| `intl` extension on | XAMPP Control Panel → Apache **Config** → `php.ini` → search `;extension=intl` → remove the `;` → save → restart Apache. |
| `mod_rewrite` on | On by default in XAMPP. |

No Composer is needed: the framework is already inside the `system/` folder.

---

## 2. Setup on a computer

1. Put the project in `htdocs\Group-6` (e.g. `D:\112\htdocs\Group-6`).
2. Start **Apache** and **MySQL** in XAMPP.
3. In phpMyAdmin (`http://localhost/phpmyadmin`), **SQL** tab, run the scripts in `database/`:

   | Your situation | Run |
   |---|---|
   | Brand-new computer (no `sun_son_solar` database yet) | `database.sql`, then `interview_features_upgrade.sql` |
   | Already have the Activity 2 database | only `interview_features_upgrade.sql` |

   `interview_features_upgrade.sql` is safe to run more than once. It never deletes accounts.
4. Open **http://localhost/Group-6/**

If your MySQL root user has a password, put it in `app/Config/Database.php`.
If your folder is not named `Group-6`, add `app.baseURL = 'http://localhost/YOUR-FOLDER/'` to the `.env` file.

### Starting accounts

| Person | Username | Password | Opens |
|---|---|---|---|
| Kat Sinagaraw (founder, Admin) | `KittyKat16` | `K@tSunShine16` | Staff area |
| Sol Solis (IT head, Admin) | `admin` | `admin123` | Staff area |
| Sample customer (Activity 1) | `customer01` | `SunSolar123!` | Customer dashboard |
| Sample employee (Activity 1) | `employee01` | `Employee123!` | Employee dashboard |

Sol said he will change `admin123` after handover (Users → Edit → Reset password).

---

## 3. Where things moved

| Old file | Now |
|---|---|
| `index.php` | `app/Views/home/index.php` (page) + `app/Controllers/Home.php` |
| `register_customer.php` | `app/Views/auth/register_customer.php` + `Auth::register / saveCustomer` |
| `login.php`, `logout.php` | `app/Controllers/Auth.php` (`login`, `logout`) |
| `customer_dashboard.php` | `app/Controllers/Customer/Dashboard.php` + `app/Views/customer/dashboard.php` |
| `employee_dashboard.php` | `app/Controllers/Employee/Dashboard.php` + `app/Views/employee/dashboard.php` |
| `admin_dashboard.php` (create employee form) | `app/Controllers/Admin/Users.php` + `app/Views/admin/user_create.php` |
| `db_connect.php` | `app/Config/Database.php` |
| `styles.css` | `public/css/styles.css` (original rules untouched; new rules added at the bottom) |
| `database.sql`, `activity2_upgrade.sql` | `database/` folder |
| "Is the user logged in?" checks at the top of each file | `app/Filters/AuthFilter.php`, attached to pages in `app/Config/Routes.php` |
| Validation `if ($firstname == "")...` | `app/Config/Validation.php` (same error messages) |
| Repeated header HTML | `app/Views/partials/header.php` (now shows who is logged in) |

```
Group-6/
├── .htaccess          sends every request into public/ (keeps app/ and system/ private)
├── app/
│   ├── Config/        Routes.php (every page), Database.php, SunSon.php (departments, roles, time slots)
│   ├── Controllers/   Home, Auth, Quote, Customer/, Employee/, Admin/
│   ├── Filters/       AuthFilter.php (login + role check)
│   ├── Helpers/       sunson_helper.php (dashboard_url, peso, status_badge...)
│   ├── Models/        one model per database table
│   └── Views/         layouts/, partials/, home/, auth/, quote/, customer/, employee/, admin/
├── database/          SQL scripts
├── public/            index.php (CodeIgniter starts here) and css/styles.css
├── system/            CodeIgniter framework (do not edit)
└── writable/          sessions and logs (not committed)
```

**For the frontend teammate:** homepage HTML is in `app/Views/home/index.php`, the header in
`app/Views/partials/header.php`, the footer in `app/Views/partials/site_footer.php`, and the CSS in
`public/css/styles.css`.

---

## 4. Features (from the client interview)

**Header fix:** when anyone is logged in, "Login" is replaced by their name, role and a link to their
dashboard, plus a Log out button, on every page.

**Homepage:** everything from Activity 2, plus a Products section (panels, inverters, batteries,
racking/mounting, wires), the full service list, a working **free quote form**, and Privacy/Terms page.

**Customers** (dashboard tabs)
- **My schedules** — book consultation, installation, maintenance, etc.; reschedule or cancel.
- **Notifications** — reminders, booking updates, promotions (unread count on the tab).
- **History & receipts** — completed services, payments, printable receipts.
- **Support** — ticketing system with chat-style replies.
- **Profile** — edit personal info, reminder/promotion preferences, change password.

**Technicians / employees** (one simple page)
- Big **TIME IN** button that saves name, time and GPS coordinates.
- **Announcements** from Admin/HR (reminders, safety, urgent).
- **Assigned jobs** with view-only customer name, address and service (no email/phone).
- **Report a problem** form that goes straight to the admin dashboard; mark a job completed.

**Staff area** (tabs depend on role)

| Section | Admin | HR | Dispatcher |
|---|---|---|---|
| Overview | ✓ | ✓ | ✓ |
| Users — add employee, view, edit, deactivate | ✓ (employees + customers) | ✓ (employees below HR) | |
| Schedules — calendar, assign technician, approve/decline, adjust, record payment | ✓ | | ✓ |
| Reports — customers, bookings, service usage, revenue charts; print | ✓ | | |
| Notifications control — send alerts, tomorrow's reminders, automatic alert switches | ✓ | | |
| Settings — service/product categories, prices, availability | ✓ | | |
| Announcements for employees | ✓ | ✓ | |
| Attendance (time-in log with map links) | ✓ | ✓ | ✓ |
| Field reports from employees | ✓ | | ✓ |
| Support tickets | ✓ | | |
| Quote requests | ✓ | | ✓ |

Roles: `admin`, `hr`, `dispatcher`, `technician`, `employee`. Only an Admin can create HR/Admin
accounts. Accounts are deactivated, never deleted.

---

## 5. Notes and assumptions to confirm with the client

- Sol's email was only given as "sol.solis"; `sol.solis@sunsonsolar.com` was assumed.
- Kat and Sol use the company phone number, as Kat asked.
- Service and product prices start blank ("Price on request"); the Admin sets them in Settings.
- The first interview said employees "register on their own"; the later interview with Sol says HR/Admin
  creates employee accounts. The later rule is used.
- Homepage testimonials, metrics and news are still sample content from the design.
- The GPS time-in works on `localhost` and HTTPS sites. Phones opening the site through a plain
  `http://192.168...` address will not share location (the time is still recorded).
