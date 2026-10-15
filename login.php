<?php
session_start();
require_once "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php#login");
    exit;
}

$account_type = trim($_POST["account_type"] ?? "");
$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";

if (($account_type != "customer" && $account_type != "employee") || $username == "" || $password == "") {
    $_SESSION["login_error"] = "Please enter a username and password in the correct login section.";
    header("Location: index.php#login");
    exit;
}

if ($account_type == "customer") {
    $stmt = $conn->prepare("SELECT customer_id, firstname, lastname, email, password FROM customers WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($user_id, $firstname, $lastname, $email, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            session_regenerate_id(true);
            $_SESSION["user_id"] = $user_id;
            $_SESSION["username"] = $username;
            $_SESSION["name"] = trim($firstname . " " . $lastname);
            $_SESSION["email"] = $email;
            $_SESSION["account_type"] = "customer";
            $_SESSION["role"] = "customer";
            $stmt->close();
            $conn->close();
            header("Location: customer_dashboard.php");
            exit;
        }
    }

    $stmt->close();
} else {
    $stmt = $conn->prepare("SELECT employee_id, firstname, lastname, department, role, password FROM employees WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($user_id, $firstname, $lastname, $department, $role, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            session_regenerate_id(true);
            $_SESSION["user_id"] = $user_id;
            $_SESSION["username"] = $username;
            $_SESSION["name"] = trim($firstname . " " . $lastname);
            $_SESSION["department"] = $department;
            $_SESSION["account_type"] = "employee";
            $_SESSION["role"] = $role;
            $stmt->close();
            $conn->close();

            if ($role == "admin" || $role == "hr") {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: employee_dashboard.php");
            }
            exit;
        }
    }

    $stmt->close();
}

$conn->close();
$_SESSION["login_error"] = "The username or password is incorrect, or the account type does not match.";
header("Location: index.php#login");
exit;
?>
