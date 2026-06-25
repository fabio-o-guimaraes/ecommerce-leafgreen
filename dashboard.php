<?php
session_start();

// Se não estiver logado vai para login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Se estiver logado mas não for admin vai para index
if ($_SESSION['user_type'] !== 'admin') {
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/69846dd936.js" crossorigin="anonymous"></script>

    <!-- Fontes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&family=Lato:wght@100;300;400;700;900&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Otimização SEO -->
    <title>Admin Dashboard - Leafgreen</title>
    <meta name="robots" content="noindex, nofollow">
</head>

<body>

    <?php require_once __DIR__ . '/includes/header.php'; ?>

    <main class="container my-5">

        <?php
        $secao = $_GET['secao'] ?? 'users';

        $secao_permitida = ['users', 'products', 'orders'];

        if (!in_array($secao, $secao_permitida)) {
            $secao = 'users';
        }
        ?>

        <h1 class="mb-4">Painel de Administração</h1>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-4 dashboard-tabs">

            <li class="nav-item">
                <a class="nav-link <?= $secao == 'users' ? 'active' : '' ?>" href="dashboard.php?secao=users">
                    Utilizadores
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= $secao == 'products' ? 'active' : '' ?>" href="dashboard.php?secao=products">
                    Produtos
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= $secao == 'orders' ? 'active' : '' ?>" href="dashboard.php?secao=orders">
                    Encomendas
                </a>
            </li>

        </ul>

        <!-- Conteúdo -->
        <?php
        if ($secao === 'products') {
            include __DIR__ . '/admin/manage_products.php';
        } elseif ($secao === 'orders') {
            include __DIR__ . '/admin/manage_orders.php';
        } else {
            include __DIR__ . '/admin/manage_users.php';
        }
        ?>

    </main>

    <?php require_once __DIR__ . '/includes/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Ligação ao ficheiro scripts -->
    <script src="assets/js/scripts.js"></script>

</body>

</html>