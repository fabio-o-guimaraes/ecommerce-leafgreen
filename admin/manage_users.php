<?php
require_once __DIR__ . '/../includes/db_connection.php';

// Buscar todos os utilizadores
$stmt = $pdo->prepare("SELECT id_utilizador, nome, email, tipo FROM utilizadores ORDER BY id_utilizador ASC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="ui_card p-4">
    <h2 class="mb-4">Gerir Utilizadores</h2>

    <?php if (isset($_GET['success']) && $_GET['success'] === 'updated'): ?>
        <div class="alert alert-success text-center">Tipo de utilizador atualizado com sucesso.</div>
    <?php endif; ?>

    <?php if (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
        <div class="alert alert-success text-center">Utilizador removido com sucesso.</div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'selfdelete'): ?>
        <div class="alert alert-danger text-center">Não podes apagar a tua própria conta.</div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'selfrole'): ?>
        <div class="alert alert-danger text-center">Não podes alterar o teu próprio tipo de conta.</div>
    <?php endif; ?>

    <div class="table-responsive table_wrap">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Tipo</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id_utilizador']) ?></td>
                        <td><?= htmlspecialchars($user['nome']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['tipo']) ?></td>

                        <td class="text-center col_accoes">
                            <div class="d-flex justify-content-center gap-2 align-items-center flex-wrap">

                                <?php if ($user['id_utilizador'] != $_SESSION['user_id']): ?>

                                    <!-- Update -->
                                    <form action="actions/update_user.php" method="POST" class="d-flex gap-2 align-items-center flex-nowrap">
                                        <input type="hidden" name="id_utilizador" value="<?= $user['id_utilizador'] ?>">

                                        <select name="tipo" class="form-select form-select-sm form_input">
                                            <option value="cliente" <?= $user['tipo'] === 'cliente' ? 'selected' : '' ?>>Cliente</option>
                                            <option value="admin" <?= $user['tipo'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                        </select>

                                        <button type="submit" class="btn btn-sm btn-primary btn_update_dashboard" title="Atualizar">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </form>

                                    <!-- Delete -->
                                    <form action="actions/delete_user.php" method="POST"
                                        onsubmit="return confirm('Tens a certeza que queres remover este utilizador?');">

                                        <input type="hidden" name="id_utilizador" value="<?= $user['id_utilizador'] ?>">

                                        <button type="submit" class="btn btn-sm btn-danger" title="Apagar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>

                            </div>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>