<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Tienda.php';

$db = (new Database())->conectar();

$tiendaModel = new Tienda($db);
$tiendas = $tiendaModel->getAll();

include __DIR__ . '/../../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Gestion de tiendas</h2>
        <p class="text-muted mb-0">
            Administracion de las tiendas registradas en BookManager.
        </p>
    </div>

    <a href="crear.php" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Nueva tienda
    </a>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_GET['msg'], ENT_QUOTES, 'UTF-8') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">Tiendas registradas</h5>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Direccion</th>
                        <th>Ciudad</th>
                        <th>Estado</th>
                        <th>Pais</th>
                        <th>Codigo postal</th>
                        <th>Terminos</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($tiendas)): ?>

                        <?php foreach ($tiendas as $t): ?>
                            <tr>
                                <td class="fw-semibold">
                                    <?= htmlspecialchars((string) $t['id_tienda'], ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td class="fw-semibold">
                                    <?= htmlspecialchars($t['nombre_tienda'], ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($t['direcc_tienda'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($t['ciudad'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($t['estado'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($t['pais'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($t['cod_postal'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($t['terminos'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td class="text-center text-nowrap">
                                    <a href="editar.php?id=<?= urlencode((string) $t['id_tienda']) ?>"
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit me-1"></i>Editar
                                    </a>

                                    <a href="eliminar.php?id=<?= urlencode((string) $t['id_tienda']) ?>"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('¿Estas seguro de eliminar esta tienda?');">
                                        <i class="fas fa-trash me-1"></i>Eliminar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="fas fa-store fa-2x mb-3 d-block"></i>
                                No hay tiendas registradas.
                            </td>
                        </tr>

                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
