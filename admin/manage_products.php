<?php
require_once __DIR__ . '/../includes/db_connection.php';

// Buscar produtos
$ativoFiltro = $_GET['ativo'] ?? '';
$stockBaixo = $_GET['stock_baixo'] ?? '';

$sql = "SELECT * FROM produtos WHERE 1=1";
$params = [];

if ($ativoFiltro !== '') {
    $sql .= " AND ativo = ?";
    $params[] = (int)$ativoFiltro;
}

if ($stockBaixo === '1') {
    $sql .= " AND stock <= 5";
}

$sql .= " ORDER BY id_produto DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="ui_card p-4">
    <h2 class="mb-4">Gerir Produtos</h2>

    <?php if (isset($_GET['success']) && $_GET['success'] === 'product_added'): ?>
        <div class="alert alert-success text-center">Produto adicionado com sucesso.</div>
    <?php endif; ?>

    <?php if (isset($_GET['success']) && $_GET['success'] === 'product_updated'): ?>
        <div class="alert alert-success text-center">Produto atualizado com sucesso.</div>
    <?php endif; ?>

    <?php if (isset($_GET['success']) && $_GET['success'] === 'product_status_changed'): ?>
        <div class="alert alert-success text-center">Estado do produto atualizado com sucesso.</div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid_file'): ?>
        <div class="alert alert-danger text-center">
            Formato de imagem inválido. Apenas são permitidos: JPG, JPEG, PNG e WEBP.
        </div>
    <?php endif; ?>

    <!-- Adicionar Produto -->
    <h3 class="mb-3">Adicionar Produto</h3>

    <form action="actions/add_product.php" method="POST" enctype="multipart/form-data" class="row g-3 mb-5">

        <div class="col-md-6">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control form_input" required>
        </div>

        <div class="col-md-3">
            <label class="form-label">Preço (€)</label>
            <input type="number" step="0.01" name="preco" class="form-control form_input" min="0" required>
        </div>

        <div class="col-md-3">
            <label class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control form_input" min="0" required>
        </div>

        <div class="col-md-12">
            <label class="form-label">Imagem</label>
            <input type="file" name="imagem" class="form-control form_input" accept="image/*">
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-success btn_update">
                Adicionar Produto
            </button>
        </div>

    </form>

    <!-- Filtro de pesquisa -->
    <form method="GET" class="row g-3 mb-4">
        <input type="hidden" name="secao" value="products">

        <div class="col-md-4">
            <label class="form-label">Estado</label>
            <select name="ativo" class="form-select form_input">
                <option value="">Todos</option>
                <option value="1" <?= ($_GET['ativo'] ?? '') === '1' ? 'selected' : '' ?>>Ativos</option>
                <option value="0" <?= ($_GET['ativo'] ?? '') === '0' ? 'selected' : '' ?>>Inativos</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Stock baixo</label>
            <select name="stock_baixo" class="form-select form_input">
                <option value="">Não</option>
                <option value="1" <?= ($_GET['stock_baixo'] ?? '') === '1' ? 'selected' : '' ?>>Sim (<= 5)</option>
            </select>
        </div>

        <div class="col-md-4 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary btn_update w-100">
                Filtrar
            </button>

            <a href="dashboard.php?secao=products" class="btn btn_finish w-100">
                Limpar
            </a>
        </div>
    </form>

    <!-- Lista Produtos -->
    <h3 class="mb-3">Produtos Existentes</h3>

    <div class="table-responsive table_wrap">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Preço (€)</th>
                    <th>Stock</th>
                    <th>Imagem</th>
                    <th class="text-center">Atualizar</th>
                    <th>Estado</th>
                    <th class="text-center">Ativar/Desativar</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($produtos as $produto): ?>
                    <tr>

                        <!-- Update form -->
                        <td><?= $produto['id_produto'] ?></td>

                        <form action="actions/update_product.php" method="POST" enctype="multipart/form-data">
                            <td class="col_nome">
                                <input type="text" name="nome"
                                    value="<?= htmlspecialchars($produto['nome']) ?>"
                                    class="form-control form-control-sm form_input" required>
                            </td>

                            <td>
                                <input type="number" step="0.01" name="preco"
                                    value="<?= htmlspecialchars($produto['preco']) ?>"
                                    class="form-control form-control-sm form_input" min="0" required>
                            </td>

                            <td>
                                <input type="number" name="stock"
                                    value="<?= htmlspecialchars($produto['stock']) ?>"
                                    class="form-control form-control-sm form_input" min="0" required>
                            </td>

                            <td>
                                <input type="file" name="imagem"
                                    class="form-control form-control-sm form_input" accept="image/*">
                                <input type="hidden" name="imagem_atual" value="<?= htmlspecialchars($produto['imagem']) ?>">
                            </td>

                            <td class="text-center">
                                <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">

                                <button type="submit" class="btn btn-sm btn-primary btn_update_dashboard" title="Atualizar">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </td>
                        </form>

                        <!-- Estado -->
                        <td>
                            <?php if ($produto['ativo'] == 1): ?>
                                <span class="badge bg-success">Ativo</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inativo</span>
                            <?php endif; ?>
                        </td>

                        <!-- Form toggle -->
                        <td class="text-center">
                            <form action="actions/toggle_product.php"
                                method="POST"
                                style="display:inline-block;">

                                <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">

                                <?php if ($produto['ativo'] == 1): ?>
                                    <button type="submit" class="btn btn-sm btn-danger" title="Desativar">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-sm btn-success btn_update_dashboard" title="Ativar">
                                        <i class="fas fa-check"></i>
                                    </button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>