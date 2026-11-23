<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Tienda.php';

$db = (new Database())->conectar();
$tiendaModel = new Tienda($db);

$errores = [];

$datos = [
    'nombre_tienda' => '',
    'direccion' => '',
    'ciudad' => '',
    'estado' => '',
    'codigo_postal' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = array_merge($datos, $_POST);

    $errores = $tiendaModel->validar($_POST);

    if (empty($errores)) {
        if ($tiendaModel->create($_POST)) {
            header('Location: index.php?msg=creado');
            exit;
        }

        $errores[] = 'No se pudo registrar la tienda.';
    }
}

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="mb-4">
    <h2 class="fw-bold mb-1">Añadir librería</h2>
    <p class="text-muted mb-0">
        Registra una nueva tienda en el sistema BookManager.
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

        <form method="POST" action="crear.php">

            <div class="mb-3">
                <label for="nombre_tienda" class="form-label">
                    Nombre de la tienda *
                </label>
                <input
                    type="text"
                    class="form-control"
                    id="nombre_tienda"
                    name="nombre_tienda"
                    maxlength="40"
                    required
                    value="<?= htmlspecialchars($datos['nombre_tienda'], ENT_QUOTES, 'UTF-8') ?>"
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
                    value="<?= htmlspecialchars($datos['direccion'], ENT_QUOTES, 'UTF-8') ?>"
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
                        value="<?= htmlspecialchars($datos['ciudad'], ENT_QUOTES, 'UTF-8') ?>"
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
                        value="<?= htmlspecialchars($datos['estado'], ENT_QUOTES, 'UTF-8') ?>"
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
                    value="<?= htmlspecialchars($datos['codigo_postal'], ENT_QUOTES, 'UTF-8') ?>"
                >
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Guardar tienda
                </button>

                <a href="index.php" class="btn btn-secondary">
                    Cancelar
                </a>
            </div>

        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
