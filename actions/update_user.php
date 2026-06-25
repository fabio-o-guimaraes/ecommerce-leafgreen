<?php
session_start();
require_once __DIR__ . '/../includes/db_connection.php';

// Apenas admin pode atualizar
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../dashboard.php?secao=users");
    exit;
}

$id_utilizador = (int) ($_POST['id_utilizador'] ?? 0);
$tipo = $_POST['tipo'] ?? null;

if (!$id_utilizador || !$tipo) {
    header("Location: ../dashboard.php?secao=users");
    exit;
}

// Só aceitar valores válidos
if (!in_array($tipo, ['admin', 'cliente'])) {
    header("Location: ../dashboard.php?secao=users");
    exit;
}

// Impedir que o admin se torne cliente acidentalmente
if ($id_utilizador == $_SESSION['user_id'] && $tipo !== 'admin') {
    header("Location: ../dashboard.php?secao=users&error=selfrole");
    exit;
}

// Atualizar tipo
$stmt = $pdo->prepare("UPDATE utilizadores SET tipo = :tipo WHERE id_utilizador = :id");
$stmt->bindParam(":tipo", $tipo);
$stmt->bindParam(":id", $id_utilizador);
$stmt->execute();

header("Location: ../dashboard.php?secao=users&success=updated");
exit;
