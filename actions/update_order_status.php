
<?php
session_start();
require_once __DIR__ . '/../includes/db_connection.php';

// Só admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../dashboard.php?secao=orders");
    exit;
}

$id_encomenda = (int) ($_POST['id_encomenda'] ?? 0);
$estado = trim($_POST['estado'] ?? '');

if ($id_encomenda <= 0 || empty($estado)) {
    header("Location: ../dashboard.php?secao=orders");
    exit;
}

// validar estados permitidos
$validos = ['pendente', 'pago', 'enviado', 'cancelado'];

if (!in_array($estado, $validos)) {
    header("Location: ../dashboard.php?secao=orders");
    exit;
}

$stmt = $pdo->prepare("UPDATE encomendas SET estado = :estado WHERE id_encomenda = :id");

$stmt->bindParam(":estado", $estado);
$stmt->bindParam(":id", $id_encomenda);

$stmt->execute();

header("Location: ../dashboard.php?secao=orders");
exit;
