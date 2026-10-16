<?php
session_start();

if (!isset($_SESSION["account_type"]) || $_SESSION["account_type"] != "employee" || !isset($_SESSION["role"]) || ($_SESSION["role"] != "admin" && $_SESSION["role"] != "hr")) {
    header("Location: index.php#login");
    exit;
}

require_once "db_connect.php";

$errors = [];
$success = "";
$firstname = "";
$lastname = "";
$middlename = "";
$birthdate = "";
$gender = "";
$email = "";
$phone = "";
$address = "";
$username = "";
$department = "";
$new_role = "employee";

function h($value) {
    return htmlspecialchars($value ?? "", ENT_QUOTES, "UTF-8");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = trim($_POST["firstname"] ?? "");
    $lastname = trim($_POST["lastname"] ?? "");
    $middlename = trim($_POST["middlename"] ?? "");
    $birthdate = trim($_POST["birthdate"] ?? "");
    $gender = trim($_POST["gender"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $address = trim($_POST["address"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $department = trim($_POST["department"] ?? "");

    // Only an Admin may create another HR account. HR users can create regular employee accounts.
    if ($_SESSION["role"] == "admin") {
        $new_role = trim($_POST["new_role"] ?? "employee");
        if ($new_role != "employee" && $new_role != "hr") {
            $new_role = "employee";
        }
    } else {
        $new_role = "employee";
    }

    if ($firstname == "") $errors[] = "First name is required.";
    if ($lastname == "") $errors[] = "Last name is required.";
    if ($birthdate == "") $errors[] = "Birthdate is required.";
    if (!in_array($gender, ["Female", "Male", "Prefer not to say"], true)) $errors[] = "Please select a valid gender option.";
    if ($email == "") {
        $errors[] = "Email address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }
    if ($phone == "") $errors[] = "Phone number is required.";
    if ($address == "") $errors[] = "Address is required.";
    if ($username == "") $errors[] = "Username is required.";
    if ($password == "") $errors[] = "Password is required.";
    if ($department == "") $errors[] = "Department is required.";

    if (empty($errors)) {
        $check_customer = $conn->prepare("SELECT customer_id FROM customers WHERE username = ?");
        $check_customer->bind_param("s", $username);
        $check_customer->execute();
        $check_customer->store_result();

        $check_employee = $conn->prepare("SELECT employee_id FROM employees WHERE username = ?");
        $check_employee->bind_param("s", $username);
        $check_employee->execute();
        $check_employee->store_result();

        if ($check_customer->num_rows > 0 || $check_employee->num_rows > 0) {
            $errors[] = "That username is already in use. Please choose another.";
        }
        $check_customer->close();
        $check_employee->close();
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO employees (firstname, lastname, middlename, birthdate, gender, email, phone, address, username, password, department, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssss", $firstname, $lastname, $middlename, $birthdate, $gender, $email, $phone, $address, $username, $hashed_password, $department, $new_role);

        if ($stmt->execute()) {
            $success = "The new " . ($new_role == "hr" ? "HR" : "employee") . " account has been created.";
            $firstname = "";
            $lastname = "";
            $middlename = "";
            $birthdate = "";
            $gender = "";
            $email = "";
            $phone = "";
            $address = "";
            $username = "";
            $department = "";
            $new_role = "employee";
        } else {
            $errors[] = "The account could not be saved. Please check the database setup and try again.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>HR / Admin | Sun Son Solar</title><link rel="stylesheet" href="styles.css"></head>
<body>
    <div class="page-shell">
        <header class="site-header"><a class="brand" href="index.php">SUN SON SOLAR</a><nav class="main-nav"><a href="index.php#about">Company home</a></nav><div class="nav-actions"><a class="btn btn-light" href="logout.php">Log out</a></div></header>
        <main class="registration-page">
            <section class="account-card">
                <p class="eyebrow">Restricted access · HR / Admin</p>
                <h1>Create an employee account</h1>
                <p>Only signed-in HR/Admin accounts can use this form. New accounts are created here instead of allowing employees to sign themselves up.</p>

                <?php if ($success != ""): ?><div class="form-message success-message" role="status"><?php echo h($success); ?></div><?php endif; ?>
                <?php if (!empty($errors)): ?><div class="form-message error-message" role="alert"><strong>Please correct the following:</strong><ul><?php foreach ($errors as $error): ?><li><?php echo h($error); ?></li><?php endforeach; ?></ul></div><?php endif; ?>

                <form class="registration-form" method="post" action="admin_dashboard.php">
                    <div class="registration-grid">
                        <label>First name *<input type="text" name="firstname" value="<?php echo h($firstname); ?>" required></label>
                        <label>Last name *<input type="text" name="lastname" value="<?php echo h($lastname); ?>" required></label>
                        <label>Middle name <span>(optional)</span><input type="text" name="middlename" value="<?php echo h($middlename); ?>"></label>
                        <label>Birthdate *<input type="date" name="birthdate" value="<?php echo h($birthdate); ?>" required></label>
                        <label>Gender *<select name="gender" required><option value="">Select one</option><option value="Female" <?php echo $gender == "Female" ? "selected" : ""; ?>>Female</option><option value="Male" <?php echo $gender == "Male" ? "selected" : ""; ?>>Male</option><option value="Prefer not to say" <?php echo $gender == "Prefer not to say" ? "selected" : ""; ?>>Prefer not to say</option></select></label>
                        <label>Email address *<input type="email" name="email" value="<?php echo h($email); ?>" required></label>
                        <label>Phone number *<input type="text" name="phone" value="<?php echo h($phone); ?>" required></label>
                        <label>Username *<input type="text" name="username" value="<?php echo h($username); ?>" autocomplete="off" required></label>
                        <label class="registration-full">Address *<input type="text" name="address" value="<?php echo h($address); ?>" required></label>
                        <label>Department *<input type="text" name="department" value="<?php echo h($department); ?>" required></label>
                        <?php if ($_SESSION["role"] == "admin"): ?>
                            <label>Account access *<select name="new_role" required><option value="employee" <?php echo $new_role == "employee" ? "selected" : ""; ?>>Employee</option><option value="hr" <?php echo $new_role == "hr" ? "selected" : ""; ?>>HR staff</option></select></label>
                        <?php endif; ?>
                        <label class="registration-full">Temporary password *<input type="password" name="password" autocomplete="new-password" required></label>
                    </div>
                    <p class="privacy-note">Share the temporary login details with the employee through an appropriate private channel.</p>
                    <div class="registration-actions"><button class="btn btn-primary" type="submit">Create account</button><a class="btn btn-light" href="index.php">Home page</a></div>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
