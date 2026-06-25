<?php
session_start();
require_once __DIR__ . '/../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login.php");
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    header("Location: ../login.php?error=empty");
    exit;
}

// PDO query
$stmt = $pdo->prepare("SELECT * FROM utilizadores WHERE email = :email");
$stmt->bindParam(':email', $email);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {

    $password_ok = password_verify($password, $user['password']);

    if ($password_ok) {

        $_SESSION['user_id'] = $user['id_utilizador'];
        $_SESSION['user_name'] = $user['nome'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_type'] = $user['tipo'];
        $_SESSION['user_morada'] = $user['morada'] ?? '';

        if ($user['tipo'] === 'admin') {
            header("Location: ../dashboard.php");
        } else {
            header("Location: ../index.php");
        }
        exit;
    }
}

header("Location: ../login.php?error=invalid");
exit;
