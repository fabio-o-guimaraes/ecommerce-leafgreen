<?php
session_start();
require_once __DIR__ . '/../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../user_register.php");
    exit;
}

// Recebe dados
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$data_nascimento = $_POST['data_nascimento'] ?? null;
$morada = trim($_POST['morada'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Guarda dados do form (SEM passwords)
$_SESSION['form_data'] = [
    'nome' => $nome,
    'email' => $email,
    'data_nascimento' => $data_nascimento,
    'morada' => $morada
];

// Validar campos obrigatórios
if (empty($nome) || empty($email) || empty($morada) || empty($password) || empty($confirm_password)) {
    header("Location: ../user_register.php?error=empty");
    exit;
}

// Validar email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../user_register.php?error=email");
    exit;
}

// Validar password
if (strlen($password) < 6) {
    header("Location: ../user_register.php?error=passlength");
    exit;
}

// Confirmar password
if ($password !== $confirm_password) {
    header("Location: ../user_register.php?error=passmatch");
    exit;
}

// Verificar se email já existe
$stmt = $pdo->prepare("SELECT id_utilizador FROM utilizadores WHERE email = :email");
$stmt->bindParam(":email", $email);
$stmt->execute();

if ($stmt->fetch()) {
    header("Location: ../user_register.php?error=exists");
    exit;
}

// Hash password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Inserir utilizador como cliente
$stmt = $pdo->prepare("
    INSERT INTO utilizadores (nome, email, password, data_nascimento, morada, tipo)
    VALUES (:nome, :email, :password, :data_nascimento, :morada, 'cliente')
");

$stmt->bindParam(":nome", $nome);
$stmt->bindParam(":email", $email);
$stmt->bindParam(":password", $password_hash);
$stmt->bindParam(":data_nascimento", $data_nascimento);
$stmt->bindParam(":morada", $morada);

if ($stmt->execute()) {
    header("Location: ../login.php?success=registered");
    exit;
} else {
    header("Location: ../user_register.php?error=fail");
    exit;
}
