<?php
session_start();
require_once __DIR__ . '/../includes/db_connection.php';

header('Content-Type: application/json');

if (!isset($_GET['id_produto'])) {
    echo json_encode([
        'status' => 'erro',
        'msg' => 'ID do produto não especificado.'
    ]);
    exit;
}

$produto_id = (int) $_GET['id_produto'];

$quantidade = isset($_GET['quantidade']) ? (int) $_GET['quantidade'] : 1;

if ($quantidade < 1) {
    $quantidade = 1;
}

// Pesquisa do produto na BD
$query = $pdo->prepare("SELECT * FROM produtos WHERE id_produto = :id");
$query->execute(['id' => $produto_id]);
$produto = $query->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    echo json_encode([
        'status' => 'erro',
        'msg' => 'Produto não encontrado.'
    ]);
    exit;
}

// Cria carrinho na sessão
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Quantidade atual no carrinho
$quantidade_atual = 0;
if (isset($_SESSION['carrinho'][$produto_id])) {
    $quantidade_atual = $_SESSION['carrinho'][$produto_id]['quantidade'];
}

// Verificar stock disponível na BD
$stock_disponivel = (int) $produto['stock'];

// Se tentar adicionar mais do que existe em stock
if ($quantidade_atual + $quantidade > $stock_disponivel) {
    echo json_encode([
        'status' => 'stock_insuficiente',
        'msg' => 'Stock insuficiente. Apenas ' . $stock_disponivel . ' disponível.'
    ]);
    exit;
}

// Adicionar produto ao carrinho, incrementa se já tiver quantidade
if (isset($_SESSION['carrinho'][$produto_id])) {
    $_SESSION['carrinho'][$produto_id]['quantidade'] += $quantidade;
} else {
    $_SESSION['carrinho'][$produto_id] = [
        'id' => $produto['id_produto'],
        'nome' => $produto['nome'],
        'preco' => $produto['preco'],
        'quantidade' => $quantidade
    ];
}

// Calcular total do carrinho
$total = 0;
foreach ($_SESSION['carrinho'] as $item) {
    $total += $item['preco'] * $item['quantidade'];
}

// Calcular total de itens no carrinho
$total_itens = 0;
foreach ($_SESSION['carrinho'] as $item) {
    $total_itens += $item['quantidade'];
}

echo json_encode([
    'status' => 'ok',
    'msg' => 'Produto adicionado ao carrinho.',
    'total' => $total,
    'total_itens' => $total_itens
]);
exit;
