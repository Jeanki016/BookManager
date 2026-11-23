<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Titulo.php';

$db = (new Database())->conectar();
$tituloModel = new Titulo($db);

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header('Location: index.php');
    exit;
}

$titulo = $tituloModel->getById($id);

if (!$titulo) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idConfirmado = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);

    if (!$idConfirmado || $idConfirmado !== $id) {
        $error = 'El identificador del título no es válido.';
    } elseif ($tituloModel->delete($id)) {
        header('Location: index.php?msg=eliminado');
        exit;
    } else {
        $error = 'No se pudo eliminar el título.';
    }
}

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="mb-4">
    <h2 class="fw-bold mb-1">Eliminar título</h2>
    <p class="text-muted mb-0">Confirma la eliminación del registro seleccionado.</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        <?php if ($error !== ''): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            ¿Estás seguro de que deseas eliminar este título?
            Esta acción no se puede deshacer.
        </div>

        <h5 class="fw-bold mb-3">Datos del título</h5>

        <p>
            <strong>ID:</strong>
            <?= (int) $titulo['id_titulo'] ?>
        </p>

        <p>
            <strong>Título:</strong>
            <?= htmlspecialchars($titulo['titulo'], ENT_QUOTES, 'UTF-8') ?>
        </p>

        <p>
            <strong>Tipo:</strong>
            <?= htmlspecialchars($titulo['tipo'] ?? 'No especificado', ENT_QUOTES, 'UTF-8') ?>
        </p>

        <p>
            <strong>Precio:</strong>
            RD$ <?= number_format((float) $titulo['precio'], 2) ?>
        </p>

        <form method="POST" action="eliminar.php?id=<?= (int) $id ?>">
            <input type="hidden" name="id" value="<?= (int) $id ?>">

            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash me-2"></i>Confirmar eliminación
            </button>

            <a href="index.php" class="btn btn-secondary">
                Cancelar
            </a>
        </form>

    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>