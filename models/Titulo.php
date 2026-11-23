<?php
declare(strict_types=1);

class Titulo
{
    private PDO $db;

    private string $titulo = '';
    private string $tipo = '';
    private ?int $id_pub = null;
    private float $precio = 0;
    private int $ventas_ytd = 0;
    private string $notas = '';
    private ?string $fecha_pub = null;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function validar(array $datos): array
    {
        $errores = [];

        $titulo = trim($datos['titulo'] ?? '');
        $tipo = trim($datos['tipo'] ?? '');
        $idPub = filter_var($datos['id_pub'] ?? null, FILTER_VALIDATE_INT);
        $precio = $datos['precio'] ?? '';
        $ventas = $datos['ventas_ytd'] ?? 0;
        $fecha = trim($datos['fecha_pub'] ?? '');

        if ($titulo === '') {
            $errores[] = 'El título del libro es obligatorio.';
        } elseif (mb_strlen($titulo) > 100) {
            $errores[] = 'El título no puede superar los 100 caracteres.';
        }

        if (mb_strlen($tipo) > 30) {
            $errores[] = 'El tipo no puede superar los 30 caracteres.';
        }

        if (!$idPub || $idPub <= 0 || !$this->publicadorExiste($idPub)) {
            $errores[] = 'Debes seleccionar una editorial válida.';
        }

        if (!is_numeric($precio) || (float) $precio < 0) {
            $errores[] = 'El precio debe ser un número igual o mayor que cero.';
        }

        if (filter_var($ventas, FILTER_VALIDATE_INT) === false || (int) $ventas < 0) {
            $errores[] = 'Las ventas deben ser un número entero igual o mayor que cero.';
        }

        if (mb_strlen(trim($datos['notas'] ?? '')) > 200) {
            $errores[] = 'Las notas no pueden superar los 200 caracteres.';
        }

        if ($fecha !== '') {
            $fechaValida = DateTime::createFromFormat('Y-m-d', $fecha);

            if (!$fechaValida || $fechaValida->format('Y-m-d') !== $fecha) {
                $errores[] = 'La fecha de publicación no es válida.';
            }
        }

        return $errores;
    }

    private function publicadorExiste(int $id): bool
    {
        $sql = "SELECT COUNT(*) FROM publicadores WHERE id_pub = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return (int) $stmt->fetchColumn() > 0;
    }

    private function cargarDatos(array $datos): void
    {
        $this->titulo = trim($datos['titulo'] ?? '');
        $this->tipo = trim($datos['tipo'] ?? '');
        $this->id_pub = (int) $datos['id_pub'];
        $this->precio = (float) $datos['precio'];
        $this->ventas_ytd = (int) ($datos['ventas_ytd'] ?? 0);
        $this->notas = trim($datos['notas'] ?? '');

        $fecha = trim($datos['fecha_pub'] ?? '');
        $this->fecha_pub = $fecha === '' ? null : $fecha;
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
                    t.ventas_ytd,
                    t.notas,
                    t.fecha_pub
                FROM titulos t
                INNER JOIN publicadores p
                    ON t.id_pub = p.id_pub
                ORDER BY t.titulo ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): array|false
    {
        if ($id <= 0) {
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
        $errores = $this->validar($datos);

        if (!empty($errores)) {
            return false;
        }

        $this->cargarDatos($datos);

        $sql = "INSERT INTO titulos
                    (titulo, tipo, id_pub, precio, ventas_ytd, notas, fecha_pub)
                VALUES
                    (:titulo, :tipo, :id_pub, :precio, :ventas, :notas, :fecha)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':titulo' => $this->titulo,
            ':tipo' => $this->tipo,
            ':id_pub' => $this->id_pub,
            ':precio' => $this->precio,
            ':ventas' => $this->ventas_ytd,
            ':notas' => $this->notas,
            ':fecha' => $this->fecha_pub
        ]);
    }

    public function update(int $id, array $datos): bool
    {
        if ($id <= 0 || !$this->getById($id)) {
            return false;
        }

        $errores = $this->validar($datos);

        if (!empty($errores)) {
            return false;
        }

        $this->cargarDatos($datos);

        $sql = "UPDATE titulos
                SET titulo = :titulo,
                    tipo = :tipo,
                    id_pub = :id_pub,
                    precio = :precio,
                    ventas_ytd = :ventas,
                    notas = :notas,
                    fecha_pub = :fecha
                WHERE id_titulo = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':titulo' => $this->titulo,
            ':tipo' => $this->tipo,
            ':id_pub' => $this->id_pub,
            ':precio' => $this->precio,
            ':ventas' => $this->ventas_ytd,
            ':notas' => $this->notas,
            ':fecha' => $this->fecha_pub,
            ':id' => $id
        ]);
    }

    public function delete(int $id): bool
    {
        if ($id <= 0 || !$this->getById($id)) {
            return false;
        }

        $sql = "DELETE FROM titulos WHERE id_titulo = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }
}
