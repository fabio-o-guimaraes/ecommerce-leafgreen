<?php
session_start();

$logado = isset($_SESSION['user_id']);

$nome = $logado ? $_SESSION['user_name'] : '';
$email = $logado ? $_SESSION['user_email'] : '';
$morada = $logado ? ($_SESSION['user_morada'] ?? '') : '';

// Impedir checkout sem carrinho // 
if (empty($_SESSION['carrinho'])) {
    header("Location: index.php");
    exit;
}
?>

<!doctype html>
<html lang="pt-PT">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Font Awesome - ícones -->
    <script src="https://kit.fontawesome.com/69846dd936.js" crossorigin="anonymous"></script>

    <!-- Fontes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&family=Lato:wght@100;300;400;700;900&display=swap" rel="stylesheet">

    <!-- Folha de estilo externa-->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Otimização SEO -->
    <title>Loja de plantas de interior - Leafgreen</title>
    <meta name="robots" content="noindex, nofollow">
</head>

<body>

    <?php require_once __DIR__ . '/includes/header.php'; ?>

    <main class="container my-5">

        <h1 class="mb-4">Finalizar Compra</h1>

        <!-- Mensagem de erro -->
        <?php if (isset($_SESSION['checkout_erro'])): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($_SESSION['checkout_erro'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <?php unset($_SESSION['checkout_erro']); ?>
        <?php endif; ?>

        <!-- Formulário -->
        <div class="row g-4">
            <section class="col-12 col-lg-8">
                <form action="actions/process_checkout.php" method="POST">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="checkout-nome" class="form-label">Nome</label>
                            <input id="checkout-nome" type="text" name="nome" class="form-control form_input" value="<?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') ?>" <?= $logado ? 'disabled' : '' ?> required> <!-- Se estiver com sessão iniciada preenche automaticamente e não permite editar (disabled) -->
                        </div>
                        <div class="col-12">
                            <label for="checkout-email" class="form-label">Email</label>
                            <input id="checkout-email" type="email" name="email" class="form-control form_input" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" <?= $logado ? 'disabled' : '' ?> required>
                        </div>
                        <div class="col-12">
                            <label for="checkout-bday" class="form-label">Data de Nascimento</label>
                            <input id="checkout-bday" type="date" name="data_nascimento" class="form-control form_input" autocomplete="bday" required>
                        </div>
                        <div class="col-12">
                            <label for="checkout-morada" class="form-label">Morada</label>
                            <input id="checkout-morada" type="text" name="morada" class="form-control form_input" value="<?= htmlspecialchars($morada, ENT_QUOTES, 'UTF-8') ?>" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn_finish w-100 mt-4">
                        Confirmar Encomenda
                    </button>
                </form>
            </section>

            <!-- Resumo -->
            <aside class="col-12 col-lg-4">
                <div class="ui_card">
                    <h2 class="mb-3">Resumo</h2>

                    <?php
                    $total = 0;
                    if (!empty($_SESSION['carrinho'])) {
                        foreach ($_SESSION['carrinho'] as $item) {
                            $total += $item['preco'] * $item['quantidade'];
                        }
                    }
                    ?>

                    <p>Total: <span class="js_total">€<?php echo number_format($total, 2, '.', ''); ?></span></p>
                </div>
            </aside>
        </div>

    </main>

    <?php require_once __DIR__ . '/includes/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <!-- Ligação ao ficheiro scripts -->
    <script src="assets/js/scripts.js"></script>

</body>

</html>