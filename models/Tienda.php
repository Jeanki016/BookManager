<?php

declare(strict_types=1);

class Tienda
{
    private PDO $db;

    private ?int $id_tienda = null;
    private string $nombre_tienda = '';
    private ?string $direccion = null;
    private ?string $ciudad = null;
    private ?string $estado = null;
    private ?string $codigo_postal = null;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Valida los datos recibidos desde el formulario.
     */
    public function validar(array $datos): array
    {
        $errores = [];

        $nombre = trim($datos['nombre_tienda'] ?? '');
        $direccion = trim($datos['direccion'] ?? '');
        $ciudad = trim($datos['ciudad'] ?? '');
        $estado = trim($datos['estado'] ?? '');
        $codigoPostal = trim($datos['codigo_postal'] ?? '');

        if ($nombre === '') {
            $errores[] = 'El nombre de la tienda es obligatorio.';
        } elseif (mb_strlen($nombre) > 40) {
            $errores[] = 'El nombre no puede superar los 40 caracteres.';
        }

        if (mb_strlen($direccion) > 80) {
            $errores[] = 'La dirección no puede superar los 80 caracteres.';
        }

        if (mb_strlen($ciudad) > 40) {
            $errores[] = 'La ciudad no puede superar los 40 caracteres.';
        }

        if (mb_strlen($estado) > 40) {
            $errores[] = 'El estado no puede superar los 40 caracteres.';
        }

        if (mb_strlen($codigoPostal) > 10) {
            $errores[] = 'El código postal no puede superar los 10 caracteres.';
        }

        return $errores;
    }

    /**
     * Carga los datos en las propiedades privadas.
     */
    private function cargarDatos(array $datos): void
    {
        $this->nombre_tienda = trim($datos['nombre_tienda'] ?? '');

        $this->direccion = $this->valorOpcional($datos['direccion'] ?? null);
        $this->ciudad = $this->valorOpcional($datos['ciudad'] ?? null);
        $this->estado = $this->valorOpcional($datos['estado'] ?? null);
        $this->codigo_postal = $this->valorOpcional($datos['codigo_postal'] ?? null);
    }

    /** Convierte campos opcionales vacíos en NULL.*/
    private function valorOpcional(?string $valor): ?string
    {
        $valor = trim($valor ?? '');

        return $valor === '' ? null : $valor;
    }

    /* Obtiene todas las tiendas.*/
    public function getAll(): array
    {
        $sql = "SELECT id_tienda, nombre_tienda, direccion,
                       ciudad, estado, codigo_postal
                FROM tiendas
                ORDER BY nombre_tienda ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* Busca una tienda por su identificador.*/
    public function getById(int $id): array|false
    {
        if ($id <= 0) {
            return false;
        }

        $sql = "SELECT id_tienda, nombre_tienda, direccion,
                       ciudad, estado, codigo_postal
                FROM tiendas
                WHERE id_tienda = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* Registra una nueva tienda. */
    public function create(array $datos): bool
    {
        $errores = $this->validar($datos);

        if (!empty($errores)) {
            return false;
        }

        $this->cargarDatos($datos);

        $sql = "INSERT INTO tiendas
                    (nombre_tienda, direccion, ciudad, estado, codigo_postal)
                VALUES
                    (:nombre, :direccion, :ciudad, :estado, :codigo_postal)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nombre' => $this->nombre_tienda,
            ':direccion' => $this->direccion,
            ':ciudad' => $this->ciudad,
            ':estado' => $this->estado,
            ':codigo_postal' => $this->codigo_postal
        ]);
    }

    /* Actualiza una tienda existente.*/
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

        $sql = "UPDATE tiendas
                SET nombre_tienda = :nombre,
                    direccion = :direccion,
                    ciudad = :ciudad,
                    estado = :estado,
                    codigo_postal = :codigo_postal
                WHERE id_tienda = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nombre' => $this->nombre_tienda,
            ':direccion' => $this->direccion,
            ':ciudad' => $this->ciudad,
            ':estado' => $this->estado,
            ':codigo_postal' => $this->codigo_postal,
            ':id' => $id
        ]);
    }

    
    public function delete(int $id): bool
    {
        if ($id <= 0) {
            return false;
        }

        $sql = "DELETE FROM tiendas WHERE id_tienda = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }
}
