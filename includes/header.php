<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header>
    <nav class="navbar navbar-expand-md navbar-dark menu_navegacao">
        <div class="container">

            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="assets/images/logos/png/logo_white.png" class="img-fluid logotipo" alt="Logótipo">
            </a>

            <!-- Botão mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Abrir menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu -->
            <div class="collapse navbar-collapse" id="navbarNav">

                <ul class="navbar-nav mx-auto">

                    <li class="nav-item">
                        <a href="index.php" class="nav-link">Loja</a>
                    </li>

                    <?php if (!isset($_SESSION['user_id'])): ?>

                        <!-- Visitante -->
                        <li class="nav-item">
                            <a href="login.php" class="nav-link">Iniciar sessão</a>
                        </li>

                        <li class="nav-item">
                            <a href="user_register.php" class="nav-link">Criar conta</a>
                        </li>

                    <?php else: ?>

                        <?php if ($_SESSION['user_type'] === 'admin'): ?>

                            <!-- Admin -->
                            <li class="nav-item">
                                <a href="dashboard.php" class="nav-link">Painel da Administração</a>
                            </li>

                        <?php endif; ?>

                        <!-- Ambos (admin e cliente) -->
                        <li class="nav-item">
                            <a href="logout.php" class="nav-link">Logout</a>
                        </li>

                    <?php endif; ?>

                </ul>

                <!-- Carrinho -->
                <a href="cart.php" class="nav-link position-relative">
                    <i class="fas fa-shopping-cart"></i>

                    <span class="js_cart_count">
                        <?php
                        $total_itens = 0;
                        if (isset($_SESSION['carrinho'])) {
                            foreach ($_SESSION['carrinho'] as $item) {
                                $total_itens += $item['quantidade'];
                            }
                        }
                        echo $total_itens;
                        ?>
                    </span>
                </a>
            </div>
        </div>
    </nav>
</header>