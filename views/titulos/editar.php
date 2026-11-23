<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Titulo.php';

$db = (new Database())->conectar();
$tituloModel = new Titulo($db);

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
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
    $errores = $tituloModel->validar($_POST);

    if (empty($errores)) {
        if ($tituloModel->update($id, $_POST)) {
            header('Location: index.php?msg=actualizado');
            exit;
        }

        $errores[] = 'No se pudo actualizar el título.';
    }

    $titulo = array_merge($titulo, $_POST);
}

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="mb-4">
    <h2 class="fw-bold mb-1">Editar título</h2>
    <p class="text-muted">Modifica la información del titulo seleccionado.</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">Informacion del titulo</h5>
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
                <label class="form-label" for="titulo">Título *</label>
                <input type="text" class="form-control" id="titulo"
                       name="titulo" maxlength="100" required
                       value="<?= htmlspecialchars($titulo['titulo'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label" for="tipo">Tipo</label>
                <input type="text" class="form-control" id="tipo"
                       name="tipo" maxlength="30"
                       value="<?= htmlspecialchars($titulo['tipo'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label" for="id_pub">Editorial *</label>
                <select class="form-select" id="id_pub" name="id_pub" required>
                    <option value="">Selecciona una editorial</option>

                    <?php foreach ($publicadores as $publicador): ?>
                        <option value="<?= (int) $publicador['id_pub'] ?>"
                            <?= (string) ($titulo['id_pub'] ?? '') === (string) $publicador['id_pub'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($publicador['nombre_pub'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="precio">Precio (RD$) *</label>
                    <input type="number" class="form-control" id="precio"
                           name="precio" min="0" step="0.01" required
                           value="<?= htmlspecialchars((string) ($titulo['precio'] ?? '0'), ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label" for="ventas_ytd">Ventas acumuladas</label>
                    <input type="number" class="form-control" id="ventas_ytd"
                           name="ventas_ytd" min="0" step="1"
                           value="<?= htmlspecialchars((string) ($titulo['ventas_ytd'] ?? '0'), ENT_QUOTES, 'UTF-8') ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="fecha_pub">Fecha de publicación</label>
                <input type="date" class="form-control" id="fecha_pub"
                       name="fecha_pub"
                       value="<?= htmlspecialchars($titulo['fecha_pub'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label" for="notas">Notas</label>
                <textarea class="form-control" id="notas" name="notas"
                          rows="3" maxlength="200"><?= htmlspecialchars($titulo['notas'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Guardar cambios
            </button>

            <a href="index.php" class="btn btn-secondary">Cancelar</a>

        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>