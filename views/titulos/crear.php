<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Titulo.php';

$db = (new Database())->conectar();
$tituloModel = new Titulo($db);

$errores = [];
$publicadores = $tituloModel->getPublicadores();

$datos = [
    'id_titulo' => '',
    'titulo' => '',
    'tipo' => '',
    'id_pub' => '',
    'precio' => '',
    'avance' => '',
    'total_ventas' => '',
    'fecha_pub' => '',
    'notas' => '',
    'contrato' => '0'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = array_merge($datos, $_POST);

    $errores = $tituloModel->validar($_POST);

    if (empty($errores)) {
        if ($tituloModel->create($_POST)) {
            header('Location: index.php?msg=creado');
            exit;
        }

        $errores[] = 'No se pudo registrar el titulo.';
    }
}

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="mb-4">
    <h2 class="fw-bold mb-1">Registrar nuevo titulo</h2>
    <p class="text-muted mb-0">
        Completa los datos para registrar un titulo en BookManager.
    </p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">
            <i class="fas fa-book me-2 text-primary"></i>
            Informacion del titulo
        </h5>
    </div>

    <div class="card-body">

        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger">
                <strong>No se pudo registrar el titulo:</strong>
                <ul class="mb-0 mt-2">
                    <?php foreach ($errores as $error): ?>
                        <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="crear.php">

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="id_titulo" class="form-label">
                        Codigo del titulo *
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="id_titulo"
                        name="id_titulo"
                        maxlength="6"
                        required
                        value="<?= htmlspecialchars((string)$datos['id_titulo'], ENT_QUOTES, 'UTF-8') ?>"
                        placeholder="Ej. BU1032"
                    >
                </div>

                <div class="col-md-8 mb-3">
                    <label for="titulo" class="form-label">
                        Titulo *
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="titulo"
                        name="titulo"
                        maxlength="60"
                        required
                        value="<?= htmlspecialchars((string)$datos['titulo'], ENT_QUOTES, 'UTF-8') ?>"
                        placeholder="Ej. El principito"
                    >
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tipo" class="form-label">Tipo</label>
                    <input
                        type="text"
                        class="form-control"
                        id="tipo"
                        name="tipo"
                        maxlength="15"
                        value="<?= htmlspecialchars((string)$datos['tipo'], ENT_QUOTES, 'UTF-8') ?>"
                        placeholder="Ej. Literatura"
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label for="id_pub" class="form-label">
                        Editorial *
                    </label>
                    <select
                        class="form-select"
                        id="id_pub"
                        name="id_pub"
                        required
                    >
                        <option value="">Selecciona una editorial</option>

                        <?php foreach ($publicadores as $publicador): ?>
                            <option
                                value="<?= htmlspecialchars((string)$publicador['id_pub'], ENT_QUOTES, 'UTF-8') ?>"
                                <?= (string)$datos['id_pub'] === (string)$publicador['id_pub'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($publicador['nombre_pub'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="precio" class="form-label">Precio (RD$)</label>
                    <input
                        type="number"
                        class="form-control"
                        id="precio"
                        name="precio"
                        min="0"
                        step="0.01"
                        value="<?= htmlspecialchars((string)$datos['precio'], ENT_QUOTES, 'UTF-8') ?>"
                    >
                </div>

                <div class="col-md-4 mb-3">
                    <label for="avance" class="form-label">Avance (RD$)</label>
                    <input
                        type="number"
                        class="form-control"
                        id="avance"
                        name="avance"
                        min="0"
                        step="0.01"
                        value="<?= htmlspecialchars((string)$datos['avance'], ENT_QUOTES, 'UTF-8') ?>"
                    >
                </div>

                <div class="col-md-4 mb-3">
                    <label for="total_ventas" class="form-label">
                        Ventas acumuladas
                    </label>
                    <input
                        type="number"
                        class="form-control"
                        id="total_ventas"
                        name="total_ventas"
                        min="0"
                        step="1"
                        value="<?= htmlspecialchars((string)$datos['total_ventas'], ENT_QUOTES, 'UTF-8') ?>"
                    >
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="fecha_pub" class="form-label">
                        Fecha de publicacion *
                    </label>
                    <input
                        type="date"
                        class="form-control"
                        id="fecha_pub"
                        name="fecha_pub"
                        required
                        value="<?= htmlspecialchars((string)$datos['fecha_pub'], ENT_QUOTES, 'UTF-8') ?>"
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label for="contrato" class="form-label">
                        Contrato *
                    </label>
                    <select
                        class="form-select"
                        id="contrato"
                        name="contrato"
                        required
                    >
                        <option value="0" <?= $datos['contrato'] === '0' ? 'selected' : '' ?>>
                            No
                        </option>
                        <option value="1" <?= $datos['contrato'] === '1' ? 'selected' : '' ?>>
                            Si
                        </option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="notas" class="form-label">Notas</label>
                <textarea
                    class="form-control"
                    id="notas"
                    name="notas"
                    rows="3"
                    maxlength="255"
                    placeholder="Informacion adicional del titulo"
                ><?= htmlspecialchars((string)$datos['notas'], ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Guardar titulo
                </button>

                <a href="index.php" class="btn btn-secondary">
                    Cancelar
                </a>
            </div>

        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
