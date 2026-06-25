<?php
require_once __DIR__ . '/../includes/db_connection.php';

// Buscar encomendas
$estado = $_GET['estado'] ?? '';
$emailFiltro = trim($_GET['email'] ?? '');

$sql = "SELECT * FROM encomendas WHERE 1=1";
$params = [];

if (!empty($estado)) {
    $sql .= " AND estado = ?";
    $params[] = $estado;
}

if (!empty($emailFiltro)) {
    $sql .= " AND email LIKE ?";
    $params[] = "%" . $emailFiltro . "%";
}

$sql .= " ORDER BY data_encomenda DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$encomendas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Se foi selecionada uma encomenda para ver detalhes
$encomenda_id = isset($_GET['ver']) ? (int)$_GET['ver'] : null;
$detalhes = [];

if ($encomenda_id) {
    $stmtDetalhes = $pdo->prepare(" SELECT ep.id_produto, ep.quantidade, ep.preco_unitario, ep.nome_produto FROM encomenda_produtos ep WHERE ep.id_encomenda = :id_encomenda");
    $stmtDetalhes->bindValue(":id_encomenda", $encomenda_id, PDO::PARAM_INT);
    $stmtDetalhes->execute();
    $detalhes = $stmtDetalhes->fetchAll(PDO::FETCH_ASSOC);
}
?>

<section class="ui_card p-4">
    <h2 class="mb-4">Gerir Encomendas</h2>

    <!-- Filtro de pesquisa  -->
    <form method="GET" class="row g-3 mb-4">
        <input type="hidden" name="secao" value="orders">

        <div class="col-md-4">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select form_input">
                <option value="">Todos</option>
                <option value="pendente" <?= ($_GET['estado'] ?? '') === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                <option value="pago" <?= ($_GET['estado'] ?? '') === 'pago' ? 'selected' : '' ?>>Pago</option>
                <option value="enviado" <?= ($_GET['estado'] ?? '') === 'enviado' ? 'selected' : '' ?>>Enviado</option>
                <option value="cancelado" <?= ($_GET['estado'] ?? '') === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
            </select>
        </div>

        <div class="col-md-5">
            <label class="form-label">Email</label>
            <input type="text" name="email" class="form-control form_input"
                value="<?= htmlspecialchars($_GET['email'] ?? '') ?>"
                placeholder="Pesquisar por email...">
        </div>

        <div class="col-md-3 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary btn_update w-100">
                Filtrar
            </button>

            <a href="dashboard.php?secao=orders" class="btn btn w-100">
                Limpar
            </a>
        </div>
    </form>

    <div class="table-responsive mb-5 table_wrap">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Total (€)</th>
                    <th>Estado</th>
                    <th>Data</th>
                    <th class="text-center">Detalhes</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($encomendas as $encomenda): ?>
                    <tr>
                        <td><?= $encomenda['id_encomenda'] ?></td>
                        <td><?= htmlspecialchars($encomenda['nome']) ?></td>
                        <td><?= htmlspecialchars($encomenda['email']) ?></td>
                        <td><?= number_format($encomenda['total'], 2, ',', '.') ?> €</td>

                        <td>
                            <form action="actions/update_order_status.php" method="POST" class="d-flex gap-2">
                                <input type="hidden" name="id_encomenda" value="<?= $encomenda['id_encomenda'] ?>">

                                <select name="estado" class="form-select form-select-sm form_input">
                                    <option value="pendente" <?= $encomenda['estado'] === 'pendente' ? 'selected' : '' ?>>
                                        Pendente
                                    </option>
                                    <option value="pago" <?= $encomenda['estado'] === 'pago' ? 'selected' : '' ?>>
                                        Pago
                                    </option>
                                    <option value="enviado" <?= $encomenda['estado'] === 'enviado' ? 'selected' : '' ?>>
                                        Enviado
                                    </option>
                                    <option value="cancelado" <?= $encomenda['estado'] === 'cancelado' ? 'selected' : '' ?>>
                                        Cancelado
                                    </option>
                                </select>

                                <button type="submit" class="btn btn-sm btn-primary btn_update_dashboard" title="Atualizar">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </form>
                        </td>

                        <td><?= $encomenda['data_encomenda'] ?></td>

                        <td class="text-center">
                            <a class="btn btn-sm btn-secondary btn_detalhes"
                                href="dashboard.php?secao=orders&ver=<?= (int)$encomenda['id_encomenda'] ?>"
                                title="Ver detalhes">
                                <i class="fas fa-search"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <?php if ($encomenda_id): ?>
        <h3>Detalhes da Encomenda #<?= $encomenda_id ?></h3>

        <?php if (count($detalhes) > 0): ?>
            <div class="table-responsive mt-3">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>ID Produto</th>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Preço Unitário (€)</th>
                            <th>Subtotal (€)</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($detalhes as $item): ?>
                            <tr>
                                <td><?= $item['id_produto'] ?></td>
                                <td><?= htmlspecialchars($item['nome_produto']) ?></td>
                                <td><?= $item['quantidade'] ?></td>
                                <td><?= number_format($item['preco_unitario'], 2, ',', '.') ?> €</td>
                                <td><?= number_format($item['preco_unitario'] * $item['quantidade'], 2, ',', '.') ?> €</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <a href="dashboard.php?secao=orders" class="btn btn-secondary btn_finish mt-3">
                Voltar
            </a>

        <?php else: ?>
            <p class="text-muted mt-3">Não existem produtos associados a esta encomenda.</p>
        <?php endif; ?>

    <?php endif; ?>
</section>