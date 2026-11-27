<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Titulo.php';

$db = (new Database())->conectar();
$tituloModel = new Titulo($db);

$id = trim((string)($_GET['id'] ?? ''));

if ($id === '' || strlen($id) > 6) {
    header('Location: index.php');
    exit;
}

$titulo = $tituloModel->getById($id);

if (!$titulo) {
    header('Location: index.php');
    exit;
}

$publicadores = $tituloModel->getPublicadores();
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = $_POST;
    $datos['id_titulo'] = $id;

    $errores = $tituloModel->validar($datos);

    if (empty($errores)) {
        if ($tituloModel->update($id, $datos)) {
            header('Location: index.php?msg=actualizado');
            exit;
        }

        $errores[] = 'No se pudo actualizar el titulo.';
    }

    $titulo = array_merge($titulo, $datos);
}

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="mb-4">
    <h2 class="fw-bold mb-1">Editar titulo</h2>
    <p class="text-muted">Modifica la informacion del titulo seleccionado.</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">Informacion del titulo</h5>
    </div>

    <div class="card-body">

        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger">
                <strong>No se pudo actualizar el titulo:</strong>
                <ul class="mb-0 mt-2">
                    <?php foreach ($errores as $error): ?>
                        <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="editar.php?id=<?= urlencode($id) ?>">

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label" for="id_titulo">
                        Codigo del titulo
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="id_titulo"
                        value="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>"
                        disabled
                    >
                </div>

                <div class="col-md-8 mb-3">
                    <label class="form-label" for="titulo">Titulo *</label>
                    <input
                        type="text"
                        class="form-control"
                        id="titulo"
                        name="titulo"
                        maxlength="60"
                        required
                        value="<?= htmlspecialchars((string)($titulo['titulo'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                    >
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="tipo">Tipo</label>
                    <input
                        type="text"
                        class="form-control"
                        id="tipo"
                        name="tipo"
                        maxlength="15"
                        value="<?= htmlspecialchars((string)($titulo['tipo'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label" for="id_pub">Editorial *</label>
                    <select class="form-select" id="id_pub" name="id_pub" required>
                        <option value="">Selecciona una editorial</option>

                        <?php foreach ($publicadores as $publicador): ?>
                            <option
                                value="<?= htmlspecialchars((string)$publicador['id_pub'], ENT_QUOTES, 'UTF-8') ?>"
                                <?= (string)($titulo['id_pub'] ?? '') === (string)$publicador['id_pub'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($publicador['nombre_pub'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label" for="precio">Precio (RD$)</label>
                    <input
                        type="number"
                        class="form-control"
                        id="precio"
                        name="precio"
                        min="0"
                        step="0.01"
                        value="<?= htmlspecialchars((string)($titulo['precio'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                    >
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label" for="avance">Avance (RD$)</label>
                    <input
                        type="number"
                        class="form-control"
                        id="avance"
                        name="avance"
                        min="0"
                        step="0.01"
                        value="<?= htmlspecialchars((string)($titulo['avance'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                    >
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label" for="total_ventas">
                        Ventas acumuladas
                    </label>
                    <input
                        type="number"
                        class="form-control"
                        id="total_ventas"
                        name="total_ventas"
                        min="0"
                        step="1"
                        value="<?= htmlspecialchars((string)($titulo['total_ventas'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                    >
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="fecha_pub">
                        Fecha de publicacion *
                    </label>
                    <input
                        type="date"
                        class="form-control"
                        id="fecha_pub"
                        name="fecha_pub"
                        required
                        value="<?= htmlspecialchars(substr((string)($titulo['fecha_pub'] ?? ''), 0, 10), ENT_QUOTES, 'UTF-8') ?>"
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label" for="contrato">Contrato *</label>
                    <select class="form-select" id="contrato" name="contrato" required>
                        <option value="0" <?= (string)($titulo['contrato'] ?? '') === '0' ? 'selected' : '' ?>>
                            No
                        </option>
                        <option value="1" <?= (string)($titulo['contrato'] ?? '') === '1' ? 'selected' : '' ?>>
                            Si
                        </option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="notas">Notas</label>
                <textarea
                    class="form-control"
                    id="notas"
                    name="notas"
                    rows="3"
                    maxlength="255"
                ><?= htmlspecialchars((string)($titulo['notas'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Guardar cambios
            </button>

            <a href="index.php" class="btn btn-secondary">Cancelar</a>

        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
