<?php
session_start();
require_once __DIR__ . '/../includes/db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../dashboard.php?secao=products");
    exit;
}

$id_produto = (int) ($_POST['id_produto'] ?? 0);

if (!$id_produto) {
    header("Location: ../dashboard.php?secao=products");
    exit;
}

// Buscar estado atual
$stmt = $pdo->prepare("SELECT ativo FROM produtos WHERE id_produto = :id");
$stmt->bindParam(":id", $id_produto);
$stmt->execute();
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    header("Location: ../dashboard.php?secao=products");
    exit;
}

$novo_estado = ($produto['ativo'] == 1) ? 0 : 1;

// Atualizar estado
$stmt = $pdo->prepare("UPDATE produtos SET ativo = :ativo WHERE id_produto = :id");
$stmt->bindParam(":ativo", $novo_estado, PDO::PARAM_INT);
$stmt->bindParam(":id", $id_produto);
$stmt->execute();

header("Location: ../dashboard.php?secao=products&success=product_updated");
exit;
