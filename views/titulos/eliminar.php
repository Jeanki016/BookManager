<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Titulo.php';

$db = (new Database())->conectar();
$tituloModel = new Titulo($db);

$id = trim((string) ($_GET['id'] ?? ''));

if ($id === '' || strlen($id) > 6) {
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
    $idConfirmado = trim((string) ($_POST['id'] ?? ''));

    if ($idConfirmado !== $id) {
        $error = 'El identificador del titulo no es valido.';
    } elseif ($tituloModel->delete($id)) {
        header('Location: index.php?msg=eliminado');
        exit;
    } else {
        $error = 'No se pudo eliminar el titulo.';
    }
}

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="mb-4">
    <h2 class="fw-bold mb-1">Eliminar titulo</h2>
    <p class="text-muted mb-0">
        Confirma la eliminacion del registro seleccionado.
    </p>
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
            ¿Estas seguro de que deseas eliminar este titulo?
            Esta accion no se puede deshacer.
        </div>

        <h5 class="fw-bold mb-3">Datos del titulo</h5>

        <p>
            <strong>ID:</strong>
            <?= htmlspecialchars($titulo['id_titulo'], ENT_QUOTES, 'UTF-8') ?>
        </p>

        <p>
            <strong>Titulo:</strong>
            <?= htmlspecialchars($titulo['titulo'], ENT_QUOTES, 'UTF-8') ?>
        </p>

        <p>
            <strong>Tipo:</strong>
            <?= htmlspecialchars($titulo['tipo'] ?? 'No especificado', ENT_QUOTES, 'UTF-8') ?>
        </p>

        <p>
            <strong>Precio:</strong>
            <?php if ($titulo['precio'] !== null): ?>
                RD$ <?= number_format((float) $titulo['precio'], 2) ?>
            <?php else: ?>
                No especificado
            <?php endif; ?>
        </p>

        <form method="POST" action="eliminar.php?id=<?= urlencode($id) ?>">
            <input type="hidden" name="id" value="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>">

            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash me-2"></i>Confirmar eliminacion
            </button>

            <a href="index.php" class="btn btn-secondary">
                Cancelar
            </a>
        </form>

    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
