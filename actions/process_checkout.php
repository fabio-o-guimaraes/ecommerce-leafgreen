<?php
session_start();
require_once __DIR__ . '/../includes/db_connection.php';

if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../checkout.php");
    exit;
}

// Recolher dados da sessão se estiver logado ou do formulário caso não esteja
$logado = isset($_SESSION['user_id']);

if ($logado) {
    $nome = $_SESSION['user_name'];
    $email = $_SESSION['user_email'];
} else {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
}


// Verifica se o email existe
if (!$logado) {

    $stmt = $pdo->prepare("SELECT id_utilizador FROM utilizadores WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        header("Location: ../checkout.php?erro=email_existe");
        exit;
    }
}

$data_nascimento = $_POST['data_nascimento'] ?? '';
$morada = trim($_POST['morada'] ?? '');

// Validar campos vazios
if ($nome === '' || $email === '' || $data_nascimento === '' || $morada === '') {
    $_SESSION['checkout_erro'] = "Preencha todos os campos.";
    header("Location: ../checkout.php");
    exit;
}

// Validar email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['checkout_erro'] = "Email inválido.";
    header("Location: ../checkout.php");
    exit;
}

// Validar idade >= 18
$dataNascimento = new DateTime($data_nascimento);
$hoje = new DateTime();
$idade = $hoje->diff($dataNascimento)->y;

if ($idade < 18) {
    $_SESSION['checkout_erro'] = "Tem de ter pelo menos 18 anos para comprar.";
    header("Location: ../checkout.php");
    exit;
}

try {
    // Se não estiver tudo ok não grava nada
    $pdo->beginTransaction();

    $total = 0;

    // Validar stock e calcular total real - sempre com base na BD 
    foreach ($_SESSION['carrinho'] as $id_produto => $item) {

        $quantidade = (int) ($item['quantidade'] ?? 0);

        if ($quantidade < 1) {
            throw new Exception("Quantidade inválida no carrinho.");
        }

        $stmt = $pdo->prepare("SELECT stock, preco, nome, ativo FROM produtos WHERE id_produto = ?");
        $stmt->execute([$id_produto]);
        $produtoBD = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$produtoBD) {
            throw new Exception("Produto não encontrado.");
        }

        if ($produtoBD['ativo'] == 0) {
            throw new Exception("Produto indisponível: " . $produtoBD['nome']);
        }

        $stockBD = (int) $produtoBD['stock'];

        if ($quantidade > $stockBD) {
            throw new Exception("Stock insuficiente para: " . $produtoBD['nome']);
        }

        $total += $produtoBD['preco'] * $quantidade;
    }

    // Inserir encomenda
    $id_utilizador = $_SESSION['user_id'] ?? null;
    $stmt = $pdo->prepare(" INSERT INTO encomendas (id_utilizador, nome, email, data_nascimento, morada, total, estado) VALUES (?, ?, ?, ?, ?, ?, 'pendente')");

    $stmt->execute([$id_utilizador, $nome, $email, $data_nascimento, $morada, $total]);

    $id_encomenda = $pdo->lastInsertId();

    // Inserir produtos e atualizar stock
    foreach ($_SESSION['carrinho'] as $id_produto => $item) {

        $stmt = $pdo->prepare("SELECT preco, stock, ativo, nome FROM produtos WHERE id_produto = ?");
        $stmt->execute([$id_produto]);
        $produtoBD = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$produtoBD) {
            throw new Exception("Produto inválido.");
        }

        if ($produtoBD['ativo'] == 0) {
            throw new Exception("Produto indisponível.");
        }

        $preco_unitario = $produtoBD['preco'];
        $quantidade = (int) ($item['quantidade'] ?? 0);

        if ($quantidade < 1) {
            throw new Exception("Quantidade inválida.");
        }

        // Inserir produto na encomenda
        $stmt = $pdo->prepare("INSERT INTO encomenda_produtos (id_encomenda, id_produto, quantidade, preco_unitario, nome_produto) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$id_encomenda, $id_produto, $quantidade, $preco_unitario, $produtoBD['nome']]);

        // Atualizar stock do produto
        $stmt = $pdo->prepare("UPDATE produtos SET stock = stock - ? WHERE id_produto = ?");
        $stmt->execute([$quantidade, $id_produto]);
    }

    $pdo->commit();

    // Guardar id encomenda para mostrar no success
    $_SESSION['ultima_encomenda'] = $id_encomenda;

    // Limpar carrinho
    unset($_SESSION['carrinho']);

    header("Location: ../success.php");
    exit;
} catch (Exception $e) {

    $pdo->rollBack();

    $_SESSION['flash_msg'][] = $e->getMessage();

    header("Location: ../cart.php");
    exit;
}
