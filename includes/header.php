<?php
require_once __DIR__ . '/../config/config.php';

$rutaActual = $_SERVER['PHP_SELF'];

$esInicio = basename($rutaActual) === 'index.php'
    && !str_contains($rutaActual, '/views/');

$esTiendas = str_contains($rutaActual, '/tiendas/');
$esTitulos = str_contains($rutaActual, '/titulos/');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>BookManager | Sistema de gestión</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #172337;
            flex-shrink: 0;
        }

        .brand {
            padding: 27px 20px;
            font-size: 21px;
            font-weight: bold;
            color: white;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }

        .sidebar .nav-link {
            color: #b8c4d6;
            padding: 13px 18px;
            margin: 5px 12px;
            border-radius: 7px;
            transition: .2s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #3157a4;
            color: white;
        }

        .sidebar .nav-link i {
            width: 25px;
        }

        .main-content {
            min-width: 0;
        }

        .topbar {
            background: white;
            padding: 17px 28px;
            border-bottom: 1px solid #e8eaf0;
        }

        .content-area {
            padding: 30px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 190px;
            }

            .content-area {
                padding: 18px;
            }
        }

        @media (max-width: 576px) {
            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                min-height: auto;
            }

            .sidebar .nav {
                flex-direction: row !important;
                flex-wrap: wrap;
            }

            .sidebar .nav-link {
                margin: 3px;
                padding: 9px 12px;
            }

            .brand {
                padding: 15px 18px;
            }
        }
    </style>
</head>

<body>

<div class="d-flex layout">

    <aside class="sidebar text-white">

        <div class="brand">
            <i class="fas fa-book-open me-2 text-primary"></i>
            BookManager
            <div class="small fw-normal mt-1 text-secondary">
                Sistema de gestión de librería
            </div>
        </div>

        <div class="px-3 pt-4">
            <small class="text-uppercase text-secondary fw-bold">
                Menú principal
            </small>
        </div>

        <nav class="nav flex-column mt-2">

            <a class="nav-link <?= $esInicio ? 'active' : '' ?>"
               href="<?= BASE_URL ?>/index.php">
                <i class="fas fa-chart-line"></i>
                Inicio
            </a>

            <a class="nav-link <?= $esTiendas ? 'active' : '' ?>"
               href="<?= BASE_URL ?>/views/tiendas/index.php">
                <i class="fas fa-store"></i>
                Tiendas
            </a>

            <a class="nav-link <?= $esTitulos ? 'active' : '' ?>"
               href="<?= BASE_URL ?>/views/titulos/index.php">
                <i class="fas fa-book"></i>
                Títulos
            </a>

        </nav>

        <div class="px-4 mt-5 text-secondary small">
            <i class="fas fa-layer-group me-2"></i>
            BookManager v1.0
        </div>

    </aside>

    <main class="flex-grow-1 main-content">

        <header class="topbar d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-0 fw-bold text-dark">
                    Panel de administración
                </h6>
                <small class="text-muted">
                    Gestión de tiendas y títulos
                </small>
            </div>

            <div class="text-muted">
                <i class="fas fa-user-circle fa-lg me-2"></i>
                Administrador
            </div>
        </header>

        <div class="content-area">
