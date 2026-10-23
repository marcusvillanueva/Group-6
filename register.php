<?php
require_once "db_connect.php";

$errors = [];
$success = "";

$account_type = "customer";
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $account_type = isset($_POST["account_type"]) ? trim($_POST["account_type"]) : "";
    $firstname = isset($_POST["firstname"]) ? trim($_POST["firstname"]) : "";
    $lastname = isset($_POST["lastname"]) ? trim($_POST["lastname"]) : "";
    $middlename = isset($_POST["middlename"]) ? trim($_POST["middlename"]) : "";
    $birthdate = isset($_POST["birthdate"]) ? trim($_POST["birthdate"]) : "";
    $gender = isset($_POST["gender"]) ? trim($_POST["gender"]) : "";
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
    $phone = isset($_POST["phone"]) ? trim($_POST["phone"]) : "";
    $address = isset($_POST["address"]) ? trim($_POST["address"]) : "";
    $username = isset($_POST["username"]) ? trim($_POST["username"]) : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";
    $department = isset($_POST["department"]) ? trim($_POST["department"]) : "";

    // validation niya par
    if ($account_type == "") {
        $errors[] = "Please select an account type.";
    } elseif ($account_type != "customer" && $account_type != "employee") {
        $errors[] = "Invalid account type.";
    }

    if ($firstname == "") {
        $errors[] = "First name is required.";
    }

    if ($lastname == "") {
        $errors[] = "Last name is required.";
    }

    if ($birthdate == "") {
        $errors[] = "Birthdate is required.";
    }

    if ($gender == "") {
        $errors[] = "Gender is required.";
    }

    if ($email == "") {
        $errors[] = "Email address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if ($phone == "") {
        $errors[] = "Phone number is required.";
    }

    if ($address == "") {
        $errors[] = "Address is required.";
    }

    if ($username == "") {
        $errors[] = "Username is required.";
    }

    if ($password == "") {
        $errors[] = "Password is required.";
    }

    if ($account_type == "employee" && $department == "") {
        $errors[] = "Department is required for employee accounts.";
    }

    // checks if username is already present in other tables
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
            $errors[] = "That username is already in use. Please choose another username.";
        }

        $check_customer->close();
        $check_employee->close();
    }

    // saves account to correct table
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        if ($account_type == "customer") {
            $stmt = $conn->prepare("INSERT INTO customers (firstname, lastname, middlename, birthdate, gender, email, phone, address, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param(
                "ssssssssss",
                $firstname,
                $lastname,
                $middlename,
                $birthdate,
                $gender,
                $email,
                $phone,
                $address,
                $username,
                $hashed_password
            );
        } else {
            $stmt = $conn->prepare("INSERT INTO employees (firstname, lastname, middlename, birthdate, gender, email, phone, address, username, password, department) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param(
                "sssssssssss",
                $firstname,
                $lastname,
                $middlename,
                $birthdate,
                $gender,
                $email,
                $phone,
                $address,
                $username,
                $hashed_password,
                $department
            );
        }

        if ($stmt->execute()) {
            $success = "Account created successfully.";

            $account_type = "customer";
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
        } else {
            $errors[] = "The account could not be saved. Please try again.";
        }

        $stmt->close();
    }
}

function old_value($value) {
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sun Son Solar | Registration</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <main class="page">
        <header class="page-header">
            <p class="brand">SUN SON SOLAR</p>
            <p class="step">REGISTRATION</p>
        </header>

        <section class="intro" aria-labelledby="page-title">
            <p class="eyebrow">Create an account</p>
            <h1 id="page-title">Start your solar journey.</h1>
            <p>Fields marked with <strong>*</strong> are required.</p>
        </section>

        <?php if (!empty($errors)): ?>
            <div class="message error-message">
                <strong>Please check the following:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo old_value($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success != ""): ?>
            <div class="message success-message">
                <?php echo old_value($success); ?>
            </div>
        <?php endif; ?>

        <form class="registration-form" method="post" action="register.php">
            <fieldset>
                <legend>Account type</legend>
                <div class="account-types">
                    <label class="choice">
                        <input type="radio" name="account_type" value="customer" <?php echo $account_type == "customer" ? "checked" : ""; ?> required>
                        Customer
                    </label>
                    <label class="choice">
                        <input type="radio" name="account_type" value="employee" <?php echo $account_type == "employee" ? "checked" : ""; ?> required>
                        Employee
                    </label>
                </div>
            </fieldset>

            <fieldset>
                <legend>Personal information</legend>
                <div class="form-grid">
                    <div class="field">
                        <label for="firstname">First name <strong>*</strong></label>
                        <input type="text" id="firstname" name="firstname" value="<?php echo old_value($firstname); ?>" required>
                    </div>

                    <div class="field">
                        <label for="lastname">Last name <strong>*</strong></label>
                        <input type="text" id="lastname" name="lastname" value="<?php echo old_value($lastname); ?>" required>
                    </div>

                    <div class="field">
                        <label for="middlename">Middle name</label>
                        <input type="text" id="middlename" name="middlename" value="<?php echo old_value($middlename); ?>">
                    </div>

                    <div class="field">
                        <label for="birthdate">Birthdate <strong>*</strong></label>
                        <input type="date" id="birthdate" name="birthdate" value="<?php echo old_value($birthdate); ?>" required>
                    </div>

                    <div class="field">
                        <label for="gender">Gender <strong>*</strong></label>
                        <select id="gender" name="gender" required>
                            <option value="">Select one</option>
                            <option value="Female" <?php echo $gender == "Female" ? "selected" : ""; ?>>Female</option>
                            <option value="Male" <?php echo $gender == "Male" ? "selected" : ""; ?>>Male</option>
                            <option value="Prefer not to say" <?php echo $gender == "Prefer not to say" ? "selected" : ""; ?>>Prefer not to say</option>
                        </select>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Contact information</legend>
                <div class="form-grid">
                    <div class="field">
                        <label for="email">Email address <strong>*</strong></label>
                        <input type="email" id="email" name="email" value="<?php echo old_value($email); ?>" required>
                    </div>

                    <div class="field">
                        <label for="phone">Phone number <strong>*</strong></label>
                        <input type="text" id="phone" name="phone" value="<?php echo old_value($phone); ?>" required>
                    </div>

                    <div class="field full-width">
                        <label for="address">Address <strong>*</strong></label>
                        <textarea id="address" name="address" rows="3" required><?php echo old_value($address); ?></textarea>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Account security</legend>
                <div class="form-grid">
                    <div class="field">
                        <label for="username">Username <strong>*</strong></label>
                        <input type="text" id="username" name="username" value="<?php echo old_value($username); ?>" required>
                    </div>

                    <div class="field">
                        <label for="password">Password <strong>*</strong></label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div class="field full-width" id="department-field">
                        <label for="department">Department <span>(employees only)</span></label>
                        <input type="text" id="department" name="department" value="<?php echo old_value($department); ?>">
                    </div>
                </div>
            </fieldset>

            <p class="privacy-note">Your information will be used to create and manage your Sun Son Solar account.</p>

            <button class="submit-button" type="submit">Create account</button>
        </form>
    </main>

    <script>
        const accountTypes = document.querySelectorAll('input[name="account_type"]');
        const departmentField = document.getElementById("department-field");
        const departmentInput = document.getElementById("department");

        function updateDepartmentField() {
            let selectedType = document.querySelector('input[name="account_type"]:checked');

            if (selectedType && selectedType.value === "employee") {
                departmentField.style.display = "block";
                departmentInput.required = true;
            } else {
                departmentField.style.display = "none";
                departmentInput.required = false;
                departmentInput.value = "";
            }
        }

        accountTypes.forEach(function (radio) {
            radio.addEventListener("change", updateDepartmentField);
        });

        updateDepartmentField();
    </script>
</body>
</html>
