<?php
session_start();
require_once __DIR__ . '/../includes/db_connection.php';

// Só admin pode apagar utilizadores
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../dashboard.php?secao=users");
    exit;
}

$id_utilizador = (int) ($_POST['id_utilizador'] ?? 0);

if (!$id_utilizador) {
    header("Location: ../dashboard.php?secao=users");
    exit;
}

// Impedir que o admin apague a própria conta
if ($id_utilizador == $_SESSION['user_id']) {
    header("Location: ../dashboard.php?secao=users&error=selfdelete");
    exit;
}

// Apagar utilizador
$stmt = $pdo->prepare("DELETE FROM utilizadores WHERE id_utilizador = :id");
$stmt->bindParam(":id", $id_utilizador);
$stmt->execute();

header("Location: ../dashboard.php?secao=users&success=deleted");
exit;
