<?php
session_start();
require_once __DIR__ . '/../includes/db_connection.php';

$mensagens = [];

if (isset($_POST['quantidades'])) {

    foreach ($_POST['quantidades'] as $id => $quantidade) {

        $id = (int) $id;
        $quantidade = (int) $quantidade;

        if ($quantidade < 1) {
            $quantidade = 1;
        }

        if (isset($_SESSION['carrinho'][$id])) {

            $query = $pdo->prepare("SELECT stock, nome FROM produtos WHERE id_produto = :id");
            $query->execute(['id' => $id]);
            $produto = $query->fetch(PDO::FETCH_ASSOC);

            if ($produto) {
                $stock_disponivel = (int) $produto['stock'];

                if ($stock_disponivel <= 0) {
                    unset($_SESSION['carrinho'][$id]);
                    $mensagens[] = "O produto \"" . $produto['nome'] . "\" está esgotado e foi removido do carrinho.";
                    continue;
                }

                if ($quantidade > $stock_disponivel) {
                    $quantidade = $stock_disponivel;
                    $mensagens[] = "Quantidade de \"" . $produto['nome'] . "\" reduzida para $stock_disponivel (stock disponível).";
                }

                $_SESSION['carrinho'][$id]['quantidade'] = $quantidade;
            }
        }
    }
}

if (!empty($mensagens)) {
    $_SESSION['flash_msg'] = array_merge($_SESSION['flash_msg'] ?? [], $mensagens);
}

header("Location: ../cart.php");
exit;
