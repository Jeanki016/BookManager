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

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idConfirmado = trim((string) ($_POST['id'] ?? ''));

    if ($idConfirmado !== $id) {
        $error = 'El identificador de la tienda no es valido.';
    } elseif ($tiendaModel->delete($id)) {
        header('Location: index.php?msg=eliminado');
        exit;
    } else {
        $error = 'No se pudo eliminar la tienda. Intentalo nuevamente.';
    }
}

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="mb-4">
    <h2 class="fw-bold mb-1">Eliminar tienda</h2>
    <p class="text-muted mb-0">
        Confirma si deseas eliminar este registro del catalogo.
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
            Esta accion eliminara permanentemente la tienda seleccionada.
        </div>

        <h5 class="fw-bold mb-3">Datos de la tienda</h5>

        <p>
            <strong>ID:</strong>
            <?= htmlspecialchars($tienda['id_tienda'], ENT_QUOTES, 'UTF-8') ?>
        </p>

        <p>
            <strong>Nombre:</strong>
            <?= htmlspecialchars($tienda['nombre_tienda'], ENT_QUOTES, 'UTF-8') ?>
        </p>

        <p>
            <strong>Direccion:</strong>
            <?= htmlspecialchars($tienda['direcc_tienda'] ?? '', ENT_QUOTES, 'UTF-8') ?>
        </p>

        <p>
            <strong>Ciudad:</strong>
            <?= htmlspecialchars($tienda['ciudad'] ?? '', ENT_QUOTES, 'UTF-8') ?>
        </p>

        <p>
            <strong>Pais:</strong>
            <?= htmlspecialchars($tienda['pais'] ?? '', ENT_QUOTES, 'UTF-8') ?>
        </p>

        <form method="POST" action="eliminar.php?id=<?= urlencode($id) ?>">
            <input
                type="hidden"
                name="id"
                value="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>"
            >

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash me-2"></i>Confirmar eliminacion
                </button>

                <a href="index.php" class="btn btn-secondary">
                    Cancelar
                </a>
            </div>
        </form>

    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
