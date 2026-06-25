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
$nome = trim($_POST['nome'] ?? '');
$preco = floatval($_POST['preco'] ?? 0);
$stock = intval($_POST['stock'] ?? 0);
$imagem = $_POST['imagem_atual'] ?? 'no_img.jpg';

/* validação de negativos */
if ($preco < 0 || $stock < 0) {
    header("Location: ../dashboard.php?secao=products");
    exit;
}

if (!empty($_FILES['imagem']['name'])) {

    $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));

    // Só permitir extensões seguras
    $extPermitidas = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $extPermitidas)) {
        header("Location: ../dashboard.php?secao=products&error=invalid_file");
        exit;
    }

    $nomeFicheiro = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $caminho = __DIR__ . '/../assets/images/products/' . $nomeFicheiro;

    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho)) {
        $imagem = $nomeFicheiro;
    }
}

if (!$id_produto || empty($nome)) {
    header("Location: ../dashboard.php?secao=products");
    exit;
}

$stmt = $pdo->prepare("
    UPDATE produtos 
    SET nome = :nome, preco = :preco, stock = :stock, imagem = :imagem
    WHERE id_produto = :id
");

$stmt->execute([
    ':nome' => $nome,
    ':preco' => $preco,
    ':stock' => $stock,
    ':imagem' => $imagem,
    ':id' => $id_produto
]);

header("Location: ../dashboard.php?secao=products&success=product_updated");
exit;
