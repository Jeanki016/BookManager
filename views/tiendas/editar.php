<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Tienda.php';

$db = (new Database())->conectar();
$tiendaModel = new Tienda($db);

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header('Location: index.php?error=id_invalido');
    exit;
}

$tienda = $tiendaModel->getById($id);

if (!$tienda) {
    header('Location: index.php?error=no_encontrado');
    exit;
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errores = $tiendaModel->validar($_POST);

    if (empty($errores)) {
        if ($tiendaModel->update($id, $_POST)) {
            header('Location: index.php?msg=actualizado');
            exit;
        }

        $errores[] = 'No se pudo actualizar la tienda.';
    }

    $tienda = array_merge($tienda, $_POST);
}

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="mb-4">
    <h2 class="fw-bold mb-1">Editar tienda</h2>
    <p class="text-muted mb-0">
        Modifica la información de la tienda seleccionada.
    </p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">Información de la tienda</h5>
    </div>

    <div class="card-body">

        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errores as $error): ?>
                        <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="editar.php?id=<?= (int) $id ?>">

            <div class="mb-3">
                <label for="nombre_tienda" class="form-label">Nombre de la tienda *</label>
                <input
                    type="text"
                    class="form-control"
                    id="nombre_tienda"
                    name="nombre_tienda"
                    maxlength="40"
                    required
                    value="<?= htmlspecialchars($tienda['nombre_tienda'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                >
            </div>

            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input
                    type="text"
                    class="form-control"
                    id="direccion"
                    name="direccion"
                    maxlength="80"
                    value="<?= htmlspecialchars($tienda['direccion'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                >
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="ciudad" class="form-label">Ciudad</label>
                    <input
                        type="text"
                        class="form-control"
                        id="ciudad"
                        name="ciudad"
                        maxlength="40"
                        value="<?= htmlspecialchars($tienda['ciudad'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <input
                        type="text"
                        class="form-control"
                        id="estado"
                        name="estado"
                        maxlength="40"
                        value="<?= htmlspecialchars($tienda['estado'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    >
                </div>
            </div>

            <div class="mb-3">
                <label for="codigo_postal" class="form-label">Código postal</label>
                <input
                    type="text"
                    class="form-control"
                    id="codigo_postal"
                    name="codigo_postal"
                    maxlength="10"
                    value="<?= htmlspecialchars($tienda['codigo_postal'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                >
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Guardar cambios
                </button>

                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </div>

        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>