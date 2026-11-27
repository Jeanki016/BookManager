<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Tienda.php';

$db = (new Database())->conectar();
$tiendaModel = new Tienda($db);

$id = trim((string) ($_GET['id'] ?? ''));

if ($id === '' || strlen($id) > 4) {
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
    $datos = $_POST;
    $datos['id_tienda'] = $id;

    $errores = $tiendaModel->validar($datos);

    if (empty($errores)) {
        if ($tiendaModel->update($id, $datos)) {
            header('Location: index.php?msg=actualizado');
            exit;
        }

        $errores[] = 'No se pudo actualizar la tienda.';
    }

    $tienda = array_merge($tienda, $datos);
}

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="mb-4">
    <h2 class="fw-bold mb-1">Editar tienda</h2>
    <p class="text-muted mb-0">
        Modifica la informacion de la tienda seleccionada.
    </p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">Informacion de la tienda</h5>
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

        <form method="POST" action="editar.php?id=<?= urlencode($id) ?>">

            <div class="mb-3">
                <label for="id_tienda" class="form-label">
                    Codigo de tienda
                </label>
                <input
                    type="text"
                    class="form-control"
                    id="id_tienda"
                    value="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>"
                    disabled
                >
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
                    value="<?= htmlspecialchars($tienda['nombre_tienda'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                >
            </div>

            <div class="mb-3">
                <label for="direcc_tienda" class="form-label">Direccion</label>
                <input
                    type="text"
                    class="form-control"
                    id="direcc_tienda"
                    name="direcc_tienda"
                    maxlength="40"
                    value="<?= htmlspecialchars($tienda['direcc_tienda'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
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
                        maxlength="2"
                        value="<?= htmlspecialchars($tienda['estado'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    >
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="pais" class="form-label">Pais</label>
                    <input
                        type="text"
                        class="form-control"
                        id="pais"
                        name="pais"
                        maxlength="3"
                        value="<?= htmlspecialchars($tienda['pais'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label for="cod_postal" class="form-label">
                        Codigo postal
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="cod_postal"
                        name="cod_postal"
                        maxlength="5"
                        value="<?= htmlspecialchars($tienda['cod_postal'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    >
                </div>
            </div>

            <div class="mb-3">
                <label for="terminos" class="form-label">
                    Terminos de pago
                </label>
                <input
                    type="text"
                    class="form-control"
                    id="terminos"
                    name="terminos"
                    maxlength="6"
                    value="<?= htmlspecialchars($tienda['terminos'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
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
