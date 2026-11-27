<?php

declare(strict_types=1);

class Titulo
{
    private PDO $db;

    private string $id_titulo = '';
    private string $titulo = '';
    private string $tipo = '';
    private string $id_pub = '';
    private ?float $precio = null;
    private ?float $avance = null;
    private ?int $total_ventas = null;
    private string $notas = '';
    private string $fecha_pub = '';
    private string $contrato = '0';

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function validar(array $datos): array
    {
        $errores = [];

        $idTitulo = trim((string)($datos['id_titulo'] ?? ''));
        $titulo = trim((string)($datos['titulo'] ?? ''));
        $tipo = trim((string)($datos['tipo'] ?? ''));
        $idPub = trim((string)($datos['id_pub'] ?? ''));
        $precio = $datos['precio'] ?? '';
        $avance = $datos['avance'] ?? '';
        $ventas = $datos['total_ventas'] ?? '';
        $notas = trim((string)($datos['notas'] ?? ''));
        $fecha = trim((string)($datos['fecha_pub'] ?? ''));
        $contrato = trim((string)($datos['contrato'] ?? ''));

        if ($idTitulo === '') {
            $errores[] = 'El codigo del titulo es obligatorio.';
        } elseif (strlen($idTitulo) > 6) {
            $errores[] = 'El codigo no puede superar los 6 caracteres.';
        }

        if ($titulo === '') {
            $errores[] = 'El titulo del libro es obligatorio.';
        } elseif (mb_strlen($titulo) > 60) {
            $errores[] = 'El titulo no puede superar los 60 caracteres.';
        }

        if (mb_strlen($tipo) > 15) {
            $errores[] = 'El tipo no puede superar los 15 caracteres.';
        }

        if ($idPub === '' || strlen($idPub) > 4 || !$this->publicadorExiste($idPub)) {
            $errores[] = 'Debes seleccionar una editorial valida.';
        }

        if ($precio !== '' && (!is_numeric($precio) || (float)$precio < 0)) {
            $errores[] = 'El precio debe ser un numero mayor o igual que cero.';
        }

        if ($avance !== '' && (!is_numeric($avance) || (float)$avance < 0)) {
            $errores[] = 'El avance debe ser un numero mayor o igual que cero.';
        }

        if ($ventas !== '' && (filter_var($ventas, FILTER_VALIDATE_INT) === false || (int)$ventas < 0)) {
            $errores[] = 'Las ventas deben ser un numero entero mayor o igual que cero.';
        }

        if (mb_strlen($notas) > 255) {
            $errores[] = 'Las notas no pueden superar los 255 caracteres.';
        }

        if ($fecha === '') {
            $errores[] = 'La fecha de publicacion es obligatoria.';
        } else {
            $fechaValida = DateTime::createFromFormat('Y-m-d', $fecha);

            if (!$fechaValida || $fechaValida->format('Y-m-d') !== $fecha) {
                $errores[] = 'La fecha de publicacion no es valida.';
            }
        }

        if (!in_array($contrato, ['0', '1'], true)) {
            $errores[] = 'El contrato debe ser 0 o 1.';
        }

        return $errores;
    }

    private function publicadorExiste(string $id): bool
    {
        $sql = "SELECT COUNT(*) FROM publicadores WHERE id_pub = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return (int)$stmt->fetchColumn() > 0;
    }

    private function cargarDatos(array $datos): void
    {
        $this->id_titulo = trim((string)$datos['id_titulo']);
        $this->titulo = trim((string)$datos['titulo']);
        $this->tipo = trim((string)($datos['tipo'] ?? ''));
        $this->id_pub = trim((string)$datos['id_pub']);

        $precio = $datos['precio'] ?? '';
        $avance = $datos['avance'] ?? '';
        $ventas = $datos['total_ventas'] ?? '';

        $this->precio = $precio === '' ? null : (float)$precio;
        $this->avance = $avance === '' ? null : (float)$avance;
        $this->total_ventas = $ventas === '' ? null : (int)$ventas;

        $this->notas = trim((string)$datos['notas']);
        $this->fecha_pub = trim((string)$datos['fecha_pub']);
        $this->contrato = trim((string)$datos['contrato']);
    }

    public function getAll(): array
    {
        $sql = "SELECT
                    t.id_titulo,
                    t.titulo,
                    t.tipo,
                    t.id_pub,
                    p.nombre_pub,
                    t.precio,
                    t.avance,
                    t.total_ventas,
                    t.notas,
                    t.fecha_pub,
                    t.contrato
                FROM titulos t
                LEFT JOIN publicadores p
                    ON t.id_pub = p.id_pub
                ORDER BY t.titulo ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(string $id): array|false
    {
        $id = trim($id);

        if ($id === '' || strlen($id) > 6) {
            return false;
        }

        $sql = "SELECT * FROM titulos WHERE id_titulo = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPublicadores(): array
    {
        $sql = "SELECT id_pub, nombre_pub
                FROM publicadores
                ORDER BY nombre_pub ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $datos): bool
    {
        if (!empty($this->validar($datos))) {
            return false;
        }

        $this->cargarDatos($datos);

        $sql = "INSERT INTO titulos
                    (id_titulo, titulo, tipo, id_pub, precio,
                     avance, total_ventas, notas, fecha_pub, contrato)
                VALUES
                    (:id_titulo, :titulo, :tipo, :id_pub, :precio,
                     :avance, :total_ventas, :notas, :fecha_pub, :contrato)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id_titulo' => $this->id_titulo,
            ':titulo' => $this->titulo,
            ':tipo' => $this->tipo,
            ':id_pub' => $this->id_pub,
            ':precio' => $this->precio,
            ':avance' => $this->avance,
            ':total_ventas' => $this->total_ventas,
            ':notas' => $this->notas,
            ':fecha_pub' => $this->fecha_pub,
            ':contrato' => $this->contrato
        ]);
    }

    public function update(string $id, array $datos): bool
    {
        $id = trim($id);

        if ($id === '' || !$this->getById($id)) {
            return false;
        }

        if (!empty($this->validar($datos))) {
            return false;
        }

        $this->cargarDatos($datos);

        $sql = "UPDATE titulos
                SET titulo = :titulo,
                    tipo = :tipo,
                    id_pub = :id_pub,
                    precio = :precio,
                    avance = :avance,
                    total_ventas = :total_ventas,
                    notas = :notas,
                    fecha_pub = :fecha_pub,
                    contrato = :contrato
                WHERE id_titulo = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':titulo' => $this->titulo,
            ':tipo' => $this->tipo,
            ':id_pub' => $this->id_pub,
            ':precio' => $this->precio,
            ':avance' => $this->avance,
            ':total_ventas' => $this->total_ventas,
            ':notas' => $this->notas,
            ':fecha_pub' => $this->fecha_pub,
            ':contrato' => $this->contrato,
            ':id' => $id
        ]);
    }

    public function delete(string $id): bool
    {
        $id = trim($id);

        if ($id === '' || !$this->getById($id)) {
            return false;
        }

        $sql = "DELETE FROM titulos WHERE id_titulo = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }
}
