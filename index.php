<?php

require_once __DIR__ . '/includes/db_connection.php';

$search = trim($_GET['search'] ?? '');
$sort = $_GET['sort'] ?? '';

$sql = "SELECT * FROM produtos WHERE ativo = 1";
$params = [];

// Pesquisa por nome
if (!empty($search)) {
    $sql .= " AND nome LIKE ?";
    $params[] = "%" . $search . "%";
}

// Ordenação
switch ($sort) {
    case 'preco_asc':
        $sql .= " ORDER BY preco ASC";
        break;
    case 'preco_desc':
        $sql .= " ORDER BY preco DESC";
        break;
    default:
        $sql .= " ORDER BY id_produto DESC";
        break;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Leafgreen - Loja de plantas de interior</title>
    <meta name="description" content="Bem-vindo à Leafgreen. Encontre plantas ideais para o seu espaço e transforme a sua casa com natureza e frescura.">
</head>

<body>

    <?php require_once __DIR__ . '/includes/header.php'; ?>

    <main>
        <!-- Secção de produtos -->
        <section class="py-5">
            <div class="container">
                <h1 class="text-center mb-5">Bem vindo à Leafgreen!</h1>
                <h2 class="text-center mb-5">Encontre a planta ideal para o seu espaço!</h2>


                <!-- Form com filtros de pesquisa -->
                <form method="GET" class="row g-3 mb-4">

                    <div class="col-md-6">
                        <input type="text" name="search"
                            class="form-control form_input"
                            placeholder="Pesquisar planta..."
                            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </div>

                    <div class="col-md-4">
                        <select name="sort" class="form-select form_input">
                            <option value="">Ordenar por...</option>
                            <option value="preco_asc" <?= ($_GET['sort'] ?? '') === 'preco_asc' ? 'selected' : '' ?>>Preço (mais baixo)</option>
                            <option value="preco_desc" <?= ($_GET['sort'] ?? '') === 'preco_desc' ? 'selected' : '' ?>>Preço (mais alto)</option>
                        </select>
                    </div>

                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn_update">
                            Filtrar
                        </button>
                    </div>
                </form>


                <div class="row g-4">
                    <?php if (count($produtos) === 0): ?>
                        <div class="alert alert-warning text-center">
                            Nenhum produto encontrado.
                        </div>
                    <?php endif; ?>

                    <?php foreach ($produtos as $produto): ?>
                        <div class="col-12 col-md-4">
                            <div class="card h-100 produto-card">
                                <img src="assets/images/products/<?php $imagem = !empty($produto['imagem']) ? $produto['imagem'] : 'no_img.jpg';
                                                                    echo htmlspecialchars($imagem, ENT_QUOTES, 'UTF-8'); ?>"
                                    class="card-img-top card_img" alt="<?php echo htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="card-body d-flex flex-column">
                                    <h3 class="card-title"><?php echo htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                    <p class="fw-bold">Preço: €<?php echo number_format($produto['preco'], 2, '.', ''); ?></p>

                                    <?php if ((int)$produto['stock'] > 0): ?>

                                        <div class="mb-2">
                                            <label class="form-label">Quantidade:</label>
                                            <input type="number"
                                                class="form-control js_qty form_input"
                                                value="1"
                                                min="1"
                                                max="<?= (int)$produto['stock']; ?>"
                                                step="1"
                                                required>
                                        </div>

                                        <button class="btn btn_update mt-auto btn_add_cart" data-id="<?= (int)$produto['id_produto']; ?>">
                                            Adicionar ao carrinho
                                        </button>

                                    <?php else: ?>

                                        <p class="text-danger fw-bold mt-2 mb-2">
                                            Sem stock
                                        </p>

                                        <button class="btn btn-secondary mt-auto" disabled>
                                            Indisponível
                                        </button>

                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Secção resumo da compra -->
        <section class="py-5 seccao-total">
            <div class="container">

                <div class="row justify-content-center">
                    <div class="col-12 col-md-6">
                        <div class="ui_card text-center">
                            <h2 class="mb-4">Resumo da compra</h2>
                            <p class="total-valor">
                                Total: <span class="js_total">€0.00</span>
                            </p>
                            <a href="cart.php" class="btn btn_finish mt-3">Finalizar compra</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php require_once __DIR__ . '/includes/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <!-- Ligação ao ficheiro scripts -->
    <script src="assets/js/scripts.js"></script>

</body>

</html>