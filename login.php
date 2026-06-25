<?php
session_start();

if (isset($_SESSION['user_id'])) {

    if ($_SESSION['user_type'] === 'admin') {
        header("Location: dashboard.php");
        exit;
    }

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
    <title>Login - Leafgreen</title>
    <meta name="description" content="Aceda à sua conta Leafgreen.">
    <meta name="robots" content="noindex, nofollow">
</head>

<body>

    <?php require_once __DIR__ . '/includes/header.php'; ?>
    <main class="container my-5">

        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="ui_card p-4">
                    <h1 class="text-center mb-4">Login</h1>

                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger text-center">
                            <?php
                            switch ($_GET['error']) {
                                case 'empty':
                                    echo "Por favor preencha todos os campos.";
                                    break;
                                case 'invalid':
                                    echo "Email ou password incorretos.";
                                    break;
                                default:
                                    echo "Ocorreu um erro. Tente novamente.";
                            }
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['success']) && $_GET['success'] === 'registered'): ?>
                        <div class="alert alert-success text-center">
                            Registado com sucesso! Pode agora iniciar sessão.
                        </div>
                    <?php endif; ?>

                    <form action="actions/process_login.php" method="POST">
                        <div class="mb-3">
                            <label for="login-email" class="form-label">Email</label>
                            <input id="login-email" name="email" type="email" class="form-control form_input" autocomplete="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="login-password" class="form-label">Password</label>
                            <input id="login-password" name="password" type="password" class="form-control form_input" autocomplete="current-password" required>
                        </div>

                        <button type="submit" class="btn btn_finish w-100 mt-2">Entrar</button>
                    </form>
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