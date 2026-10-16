<?php
session_start();
require_once "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php#login");
    exit;
}

if (isset($_SESSION["user_id"], $_SESSION["account_type"])) {
    $signed_in_name = $_SESSION["name"] ?? $_SESSION["username"] ?? "your current account";
    $_SESSION["login_error"] = "You are already signed in as " . $signed_in_name . ". Log out before signing in with another account.";
    header("Location: index.php#login");
    exit;
}

$account_type = trim($_POST["account_type"] ?? "");
$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";
$stmt = null;

if ($account_type == "customer") {
    $stmt = $conn->prepare("SELECT customer_id, firstname, lastname, email, password FROM customers WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
} elseif ($account_type == "employee") {
    $stmt = $conn->prepare("SELECT employee_id, firstname, lastname, department, role, password FROM employees WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
} else {
    $_SESSION["login_error"] = "Choose a valid account type.";
    header("Location: index.php#login");
    exit;
}

$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 1) {
    if ($account_type == "customer") {
        $stmt->bind_result($user_id, $firstname, $lastname, $email, $password_hash);
        $stmt->fetch();
        $role = "customer";
        $department = "";
    } else {
        $stmt->bind_result($user_id, $firstname, $lastname, $department, $role, $password_hash);
        $stmt->fetch();
        $email = "";
    }

    if (password_verify($password, $password_hash)) {
        session_regenerate_id(true);
        $_SESSION["user_id"] = $user_id;
        $_SESSION["username"] = $username;
        $_SESSION["name"] = trim($firstname . " " . $lastname);
        $_SESSION["email"] = $email;
        $_SESSION["department"] = $department;
        $_SESSION["account_type"] = $account_type;
        $_SESSION["role"] = $role;
        $stmt->close();
        $conn->close();
        header("Location: index.php#login");
        exit;
    }
}

$stmt->close();
$conn->close();
$_SESSION["login_error"] = "The username or password is incorrect.";
header("Location: index.php#login");
exit;