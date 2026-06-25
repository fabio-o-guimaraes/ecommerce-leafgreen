<?php
session_start();

$id_encomenda = $_SESSION['ultima_encomenda'] ?? null;
unset($_SESSION['ultima_encomenda']);


if (!$id_encomenda) {
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
    <title>Encomenda confirmada - Leafgreen</title>
    <meta name="robots" content="noindex, nofollow">
</head>

<body>

    <?php require_once __DIR__ . '/includes/header.php'; ?>

    <main class="container my-5">

        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">

                <div class="ui_card p-5 text-center">

                    <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>

                    <h1 class="mb-3">Encomenda confirmada!</h1>

                    <p class="mb-4">
                        Obrigado pela sua compra. A sua encomenda foi registada com sucesso.
                    </p>
                    <?php if ($id_encomenda): ?>
                        <p><strong>Número da encomenda:</strong> #<?php echo (int)$id_encomenda; ?></p>
                    <?php endif; ?>

                    <a href="index.php" class="btn btn_finish">
                        Voltar à loja
                    </a>

                    <button onclick="window.print()" class="btn btn_update">
                        Imprimir/Guardar comprovativo (PDF)
                    </button>

                </div>

            </div>
        </div>

    </main>


    <?php require_once __DIR__ . '/includes/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <!-- Ligação ao ficheiro scripts -->
    <script src="assets/js/scripts.js"></script>

</body>

</html>