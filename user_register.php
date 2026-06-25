<?php
session_start();

if (isset($_SESSION['user_id'])) {
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
    <title>Criar conta - Leafgreen</title>
    <meta name="description" content="Crie a sua conta Leafgreen e compre plantas facilmente.">
    <meta name="robots" content="noindex, nofollow">
</head>

<body>

    <?php require_once __DIR__ . '/includes/header.php'; ?>

    <main class="container my-5">

        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">

                <div class="ui_card p-4">
                    <h1 class="text-center mb-4">Criar Conta</h1>

                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger text-center">
                            <?php
                            switch ($_GET['error']) {
                                case 'empty':
                                    echo "Por favor preencha todos os campos obrigatórios.";
                                    break;
                                case 'email':
                                    echo "Email inválido.";
                                    break;
                                case 'passlength':
                                    echo "A password deve ter pelo menos 6 caracteres.";
                                    break;
                                case 'passmatch':
                                    echo "As passwords não coincidem.";
                                    break;
                                case 'exists':
                                    echo "Já existe uma conta registada com este email.";
                                    break;
                                case 'fail':
                                    echo "Erro ao criar conta. Tenta novamente.";
                                    break;
                                default:
                                    echo "Ocorreu um erro. Tente novamente.";
                            }
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php
                    $form = $_SESSION['form_data'] ?? [];
                    unset($_SESSION['form_data']);
                    ?>

                    <form action="actions/process_register.php" method="POST">

                        <div class="mb-3">
                            <label for="register-nome" class="form-label">Nome</label>
                            <input id="register-nome" name="nome" type="text" class="form-control form_input"
                                placeholder="O seu nome" autocomplete="name" required value="<?php echo htmlspecialchars($form['nome'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="register-email" class="form-label">Email</label>
                            <input id="register-email" name="email" type="email" class="form-control form_input"
                                placeholder="exemplo@email.com" autocomplete="email" required value="<?php echo htmlspecialchars($form['email'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="register-bday" class="form-label">Data de Nascimento</label>
                            <input id="register-bday" name="data_nascimento" type="date" class="form-control form_input"
                                autocomplete="bday" value="<?php echo htmlspecialchars($form['data_nascimento'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="register-morada" class="form-label">Morada</label>
                            <input id="register-morada" name="morada" type="text" class="form-control form_input"
                                placeholder="Rua, nº, cidade..." autocomplete="street-address" value="<?php echo htmlspecialchars($form['morada'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="register-password" class="form-label">Password</label>
                            <input id="register-password" name="password" type="password" class="form-control form_input"
                                autocomplete="new-password" required>
                        </div>

                        <div class="mb-4">
                            <label for="register-password-confirm" class="form-label">Confirmar Password</label>
                            <input id="register-password-confirm" name="confirm_password" type="password" class="form-control form_input"
                                autocomplete="new-password" required>
                        </div>

                        <button type="submit" class="btn btn_finish w-100">Registar</button>
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