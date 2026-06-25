<?php
session_start();
?>

<!doctype html>
<html lang="pt-PT">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome - ícones -->
    <script src="https://kit.fontawesome.com/69846dd936.js" crossorigin="anonymous"></script>

    <!-- Fontes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&family=Lato:wght@100;300;400;700;900&display=swap" rel="stylesheet">

    <!-- Folha de estilo externa-->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Otimização SEO -->
    <title>Carrinho - Leafgreen</title>
    <meta name="robots" content="noindex, nofollow">
</head>

<body>

    <?php require_once __DIR__ . '/includes/header.php'; ?>

    <main class="container my-5">
        <h1 class="mb-4">Carrinho</h1>

        <!-- Mensagem caso o stock seja superior ao disponível na BD -->
        <?php if (isset($_SESSION['flash_msg'])): ?>
            <div class="alert alert-warning">
                <?php foreach ($_SESSION['flash_msg'] as $msg): ?>
                    <p class="mb-0"><?php echo htmlspecialchars($msg); ?></p>
                <?php endforeach; ?>
            </div>
            <?php unset($_SESSION['flash_msg']); ?>
        <?php endif; ?>

        <!-- Verificação de carrinho vazio -->
        <?php if (!empty($_SESSION['carrinho'])): ?>

            <form action="actions/update_cart.php" method="post">

                <?php $total = 0; ?>

                <?php foreach ($_SESSION['carrinho'] as $id => $produto): ?>
                    <?php
                    $subtotal = $produto['preco'] * $produto['quantidade'];
                    $total += $subtotal;
                    ?>

                    <article class="ui_card d-flex justify-content-between align-items-center p-3 mb-3">
                        <div>
                            <h2 class="mb-1 cart-title"><?php echo htmlspecialchars($produto['nome']); ?></h2>
                            <p class="mb-0 cart-total">Preço unitário: €<?php echo number_format($produto['preco'], 2, '.', ''); ?></p>
                            <p class="mb-0 cart-total">Subtotal: €<?php echo number_format($subtotal, 2, '.', ''); ?></p>
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <input type="number" name="quantidades[<?php echo $id; ?>]" value="<?php echo (int)$produto['quantidade']; ?>" min="1" class="form-control form_input" style="width: 80px;">

                            <a href="actions/remove_from_cart.php?id=<?php echo (int)$id; ?>" class="btn btn-sm btn-danger">
                                Remover
                            </a>
                        </div>
                    </article>

                <?php endforeach; ?>

                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn_update">
                        Atualizar Carrinho
                    </button>

                    <h2>Total: €<?php echo number_format($total, 2, '.', ''); ?></h2>
                </div>

                <div class="mt-4 text-end">
                    <a href="index.php" class="btn btn_update mt-3">
                        Continuar a comprar
                    </a>
                    <a href="checkout.php" class="btn btn_finish mt-3">
                        Finalizar Compra
                    </a>
                </div>
                <div class="mt-4 text-end">

                </div>

            </form>

        <?php else: ?>

            <p>O seu carrinho está vazio.</p>
            <a href="index.php" class="btn btn_update">Voltar à loja</a>

        <?php endif; ?>

    </main>

    <?php require_once __DIR__ . '/includes/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <!-- Ligação ao ficheiro scripts -->
    <script src="assets/js/scripts.js"></script>

</body>

</html>