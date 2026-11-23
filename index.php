<?php
require_once __DIR__ . '/config/database.php';

$db = (new Database())->conectar();

$stmtTiendas = $db->prepare("SELECT COUNT(*) FROM tiendas");
$stmtTiendas->execute();
$totalTiendas = (int) $stmtTiendas->fetchColumn();

$stmtTitulos = $db->prepare("SELECT COUNT(*) FROM titulos");
$stmtTitulos->execute();
$totalTitulos = (int) $stmtTitulos->fetchColumn();

$stmtPublicadores = $db->prepare("SELECT COUNT(*) FROM publicadores");
$stmtPublicadores->execute();
$totalPublicadores = (int) $stmtPublicadores->fetchColumn();

include __DIR__ . '/includes/header.php';
?>

<div class="mb-4">
    <h2 class="fw-bold mb-1">Panel principal</h2>
    <p class="text-muted">
        Bienvenido a BookManager, tu sistema de gestion de librería.
    </p>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <span class="badge bg-primary mb-3">BOOKMANAGER</span>

                <h3 class="fw-bold">
                    Administra tu librería desde un solo lugar
                </h3>

                <p class="text-muted mb-3">
                    Gestiona las tiendas y los títulos registrados
                    en tu base de datos de manera sencilla y organizada.
                </p>

                <a href="<?= BASE_URL ?>/views/tiendas/index.php"
                   class="btn btn-primary me-2">
                    <i class="fas fa-store me-2"></i>Gestionar tiendas
                </a>

                <a href="<?= BASE_URL ?>/views/titulos/index.php"
                   class="btn btn-outline-primary">
                    <i class="fas fa-book me-2"></i>Gestionar títulos
                </a>
            </div>

            <div class="col-md-4 text-center mt-4 mt-md-0">
                <i class="fas fa-book-open text-primary"
                   style="font-size: 100px;"></i>
            </div>
        </div>
    </div>
</div>

<h5 class="fw-bold mb-3">Resumen de registros</h5>

<div class="row g-3 mb-4">

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Tiendas registradas</p>
                        <h2 class="fw-bold mb-0"><?= $totalTiendas ?></h2>
                    </div>

                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="fas fa-store text-primary fa-2x"></i>
                    </div>
                </div>

                <a href="<?= BASE_URL ?>/views/tiendas/index.php"
                   class="small text-decoration-none mt-3 d-inline-block">
                    Ver tiendas <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Titulos registrados</p>
                        <h2 class="fw-bold mb-0"><?= $totalTitulos ?></h2>
                    </div>

                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="fas fa-book text-success fa-2x"></i>
                    </div>
                </div>

                <a href="<?= BASE_URL ?>/views/titulos/index.php"
                   class="small text-decoration-none mt-3 d-inline-block">
                    Ver títulos <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Editoriales registradas</p>
                        <h2 class="fw-bold mb-0"><?= $totalPublicadores ?></h2>
                    </div>

                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="fas fa-building text-warning fa-2x"></i>
                    </div>
                </div>

                <span class="small text-muted mt-3 d-inline-block">
                    Datos de publicadores
                </span>
            </div>
        </div>
    </div>

</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-2">
            <i class="fas fa-info-circle text-primary me-2"></i>
            Información del sistema
        </h5>

        <p class="text-muted mb-0">
            BookManager permite administrar los registros de tiendas y
            títulos utilizando PHP orientado a objetos, PDO y MySQL.
        </p>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
