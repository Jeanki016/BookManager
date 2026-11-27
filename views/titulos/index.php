<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Titulo.php';

$db = (new Database())->conectar();
$tituloModel = new Titulo($db);

$titulos = $tituloModel->getAll();

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Gestion de títulos</h2>
        <p class="text-muted mb-0">
            Administra el catálogo de libros de la librería.
        </p>
    </div>

    <a href="crear.php" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Nuevo título
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">Catalogo de títulos</h5>
    </div>

    <div class="card-body">
        <?php if (empty($titulos)): ?>
            <div class="text-center py-5">
                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                <h5>No hay títulos registrados</h5>
                <p class="text-muted">
                    Agrega el primer título al catálogo.
                </p>
            </div>
        <?php else: ?>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Tipo</th>
                            <th>Editorial</th>
                            <th>Precio</th>
                            <th>Ventas</th>
                            <th>Fecha de publicación</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($titulos as $titulo): ?>
                            <tr>
                                <td>
                                    <?= htmlspecialchars((string) $titulo['id_titulo'], ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td class="fw-semibold">
                                    <?= htmlspecialchars($titulo['titulo'], ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($titulo['tipo'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($titulo['nombre_pub'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <?php if ($titulo['precio'] !== null): ?>
                                        RD$ <?= number_format((float) $titulo['precio'], 2) ?>
                                    <?php else: ?>
                                        No especificado
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= number_format((int) ($titulo['total_ventas'] ?? 0)) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $titulo['fecha_pub'] ?? 'No especificada',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </td>

                                <td class="text-center">
                                    <a href="editar.php?id=<?= urlencode((string) $titulo['id_titulo']) ?>"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a href="eliminar.php?id=<?= urlencode((string) $titulo['id_titulo']) ?>"
                                       class="btn btn-sm btn-outline-danger"
                                       title="Eliminar"
                                       onclick="return confirm('¿Estás seguro de eliminar este título?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
