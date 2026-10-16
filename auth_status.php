<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

$is_logged_in = isset($_SESSION["user_id"], $_SESSION["account_type"]);
$role = strtolower($_SESSION["role"] ?? "");
$account_type = $_SESSION["account_type"] ?? "";
$error = $_SESSION["login_error"] ?? "";
unset($_SESSION["login_error"]);

echo json_encode([
    "logged_in" => $is_logged_in,
    "name" => $_SESSION["name"] ?? "",
    "role" => $role,
    "account_type" => $account_type,
    "error" => $error,
]);