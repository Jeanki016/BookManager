<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Tienda.php';

$db = (new Database())->conectar();
$tiendaModel = new Tienda($db);

$errores = [];

$datos = [
    'id_tienda' => '',
    'nombre_tienda' => '',
    'direcc_tienda' => '',
    'ciudad' => '',
    'estado' => '',
    'pais' => '',
    'cod_postal' => '',
    'terminos' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = array_merge($datos, $_POST);

    $errores = $tiendaModel->validar($datos);

    if (empty($errores)) {
        if ($tiendaModel->create($datos)) {
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
                <label for="id_tienda" class="form-label">
                    Código de tienda *
                </label>
                <input
                    type="text"
                    class="form-control"
                    id="id_tienda"
                    name="id_tienda"
                    maxlength="4"
                    required
                    value="<?= htmlspecialchars($datos['id_tienda'], ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="Ejemplo: 7067"
                >
                <small class="text-muted">
                    Código de máximo 4 caracteres.
                </small>
            </div>

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
                <label for="direcc_tienda" class="form-label">
                    Dirección
                </label>
                <input
                    type="text"
                    class="form-control"
                    id="direcc_tienda"
                    name="direcc_tienda"
                    maxlength="40"
                    value="<?= htmlspecialchars($datos['direcc_tienda'], ENT_QUOTES, 'UTF-8') ?>"
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
                        maxlength="15"
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
                        maxlength="2"
                        value="<?= htmlspecialchars($datos['estado'], ENT_QUOTES, 'UTF-8') ?>"
                    >
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="pais" class="form-label">País</label>
                    <input
                        type="text"
                        class="form-control"
                        id="pais"
                        name="pais"
                        maxlength="3"
                        value="<?= htmlspecialchars($datos['pais'], ENT_QUOTES, 'UTF-8') ?>"
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label for="cod_postal" class="form-label">
                        Código postal
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="cod_postal"
                        name="cod_postal"
                        maxlength="5"
                        value="<?= htmlspecialchars($datos['cod_postal'], ENT_QUOTES, 'UTF-8') ?>"
                    >
                </div>
            </div>

            <div class="mb-3">
                <label for="terminos" class="form-label">
                    Términos de pago
                </label>
                <input
                    type="text"
                    class="form-control"
                    id="terminos"
                    name="terminos"
                    maxlength="6"
                    value="<?= htmlspecialchars($datos['terminos'], ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="Ejemplo: Net 30"
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
