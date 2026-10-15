<?php
require_once "db_connect.php";

$errors = [];
$firstname = "";
$lastname = "";
$middlename = "";
$birthdate = "";
$gender = "";
$email = "";
$phone = "";
$address = "";
$username = "";

function h($value) {
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
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
        $stmt = $conn->prepare("INSERT INTO customers (firstname, lastname, middlename, birthdate, gender, email, phone, address, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $firstname, $lastname, $middlename, $birthdate, $gender, $email, $phone, $address, $username, $hashed_password);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: index.php?registered=1#login");
            exit;
        } else {
            $errors[] = "The customer account could not be created. Please try again.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Sign Up | Sun Son Solar</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="page-shell">
        <header class="site-header">
            <a class="brand" href="index.php">SUN SON SOLAR</a>
            <nav class="main-nav"><a href="index.php#about">About</a><a href="index.php#services">Services</a><a href="index.php#support">Support</a></nav>
            <div class="nav-actions"><a class="btn btn-light" href="index.php#login">Login</a></div>
        </header>

        <main class="registration-page">
            <section class="account-card">
                <div class="section-heading"><p class="eyebrow">Customer registration</p><h1>Create your account</h1><p>Fill out the required fields below. Employee accounts must be created by HR or Admin.</p></div>

                <?php if (!empty($errors)): ?>
                    <div class="form-message error-message" role="alert"><strong>Please correct the following:</strong><ul><?php foreach ($errors as $error): ?><li><?php echo h($error); ?></li><?php endforeach; ?></ul></div>
                <?php endif; ?>

                <form class="registration-form" method="post" action="register_customer.php">
                    <div class="registration-grid">
                        <label>First name *<input type="text" name="firstname" value="<?php echo h($firstname); ?>" required></label>
                        <label>Last name *<input type="text" name="lastname" value="<?php echo h($lastname); ?>" required></label>
                        <label>Middle name <span>(optional)</span><input type="text" name="middlename" value="<?php echo h($middlename); ?>"></label>
                        <label>Birthdate *<input type="date" name="birthdate" value="<?php echo h($birthdate); ?>" required></label>
                        <label>Gender *<select name="gender" required><option value="">Select one</option><option value="Female" <?php echo $gender == "Female" ? "selected" : ""; ?>>Female</option><option value="Male" <?php echo $gender == "Male" ? "selected" : ""; ?>>Male</option><option value="Prefer not to say" <?php echo $gender == "Prefer not to say" ? "selected" : ""; ?>>Prefer not to say</option></select></label>
                        <label>Email address *<input type="email" name="email" value="<?php echo h($email); ?>" required></label>
                        <label>Phone number *<input type="text" name="phone" value="<?php echo h($phone); ?>" required></label>
                        <label>Username *<input type="text" name="username" value="<?php echo h($username); ?>" autocomplete="username" required></label>
                        <label class="registration-full">Address *<input type="text" name="address" value="<?php echo h($address); ?>" required></label>
                        <label class="registration-full">Password *<input type="password" name="password" autocomplete="new-password" required></label>
                    </div>
                    <p class="privacy-note">Your password is stored as a hash, not as readable text.</p>
                    <div class="registration-actions"><button class="btn btn-primary" type="submit">Create customer account</button><a class="btn btn-light" href="index.php">Cancel</a></div>
                </form>
            </section>
        </main>

        <footer class="registration-footer"><a href="index.php">Return to Sun Son Solar home page</a></footer>
    </div>
</body>
</html>
