<?php

declare(strict_types=1);

class Tienda
{
    private PDO $db;

    private string $id_tienda = '';
    private string $nombre_tienda = '';
    private string $direcc_tienda = '';
    private string $ciudad = '';
    private string $estado = '';
    private string $pais = '';
    private string $cod_postal = '';
    private string $terminos = '';

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

   
    public function validar(array $datos): array
    {
        $errores = [];

        $id = trim((string) ($datos['id_tienda'] ?? ''));
        $nombre = trim((string) ($datos['nombre_tienda'] ?? ''));
        $direccion = trim((string) ($datos['direcc_tienda'] ?? $datos['direccion'] ?? ''));
        $ciudad = trim((string) ($datos['ciudad'] ?? ''));
        $estado = trim((string) ($datos['estado'] ?? ''));
        $pais = trim((string) ($datos['pais'] ?? ''));
        $codigoPostal = trim((string) ($datos['cod_postal'] ?? $datos['codigo_postal'] ?? ''));
        $terminos = trim((string) ($datos['terminos'] ?? ''));

        if ($id === '') {
            $errores[] = 'El codigo de la tienda es obligatorio.';
        } elseif (strlen($id) > 4) {
            $errores[] = 'El codigo no puede superar los 4 caracteres.';
        }

        if ($nombre === '') {
            $errores[] = 'El nombre de la tienda es obligatorio.';
        } elseif (mb_strlen($nombre) > 40) {
            $errores[] = 'El nombre no puede superar los 40 caracteres.';
        }

        if (mb_strlen($direccion) > 40) {
            $errores[] = 'La direccion no puede superar los 40 caracteres.';
        }

        if (mb_strlen($ciudad) > 15) {
            $errores[] = 'La ciudad no puede superar los 15 caracteres.';
        }

        if (mb_strlen($estado) > 2) {
            $errores[] = 'El estado no puede superar los 2 caracteres.';
        }

        if (mb_strlen($pais) > 3) {
            $errores[] = 'El pais no puede superar los 3 caracteres.';
        }

        if (mb_strlen($codigoPostal) > 5) {
            $errores[] = 'El codigo postal no puede superar los 5 caracteres.';
        }

        if (mb_strlen($terminos) > 6) {
            $errores[] = 'Los terminos no pueden superar los 6 caracteres.';
        }

        return $errores;
    }

 
    private function cargarDatos(array $datos): void
    {
        $this->id_tienda = trim((string) ($datos['id_tienda'] ?? ''));
        $this->nombre_tienda = trim((string) ($datos['nombre_tienda'] ?? ''));

        $this->direcc_tienda = trim((string) ($datos['direcc_tienda'] ?? $datos['direccion'] ?? ''));
        $this->ciudad = trim((string) ($datos['ciudad'] ?? ''));
        $this->estado = trim((string) ($datos['estado'] ?? ''));
        $this->pais = trim((string) ($datos['pais'] ?? ''));
        $this->cod_postal = trim((string) ($datos['cod_postal'] ?? $datos['codigo_postal'] ?? ''));
        $this->terminos = trim((string) ($datos['terminos'] ?? ''));
    }

 
    public function getAll(): array
    {
        $sql = "SELECT id_tienda, nombre_tienda, direcc_tienda,
                       ciudad, estado, pais, cod_postal, terminos
                FROM tiendas
                ORDER BY nombre_tienda ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
    public function getById(string $id): array|false
    {
        $id = trim($id);

        if ($id === '' || strlen($id) > 4) {
            return false;
        }

        $sql = "SELECT id_tienda, nombre_tienda, direcc_tienda,
                       ciudad, estado, pais, cod_postal, terminos
                FROM tiendas
                WHERE id_tienda = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    public function create(array $datos): bool
    {
        if (!empty($this->validar($datos))) {
            return false;
        }

        $this->cargarDatos($datos);

        $sql = "INSERT INTO tiendas
                    (id_tienda, nombre_tienda, direcc_tienda,
                     ciudad, estado, pais, cod_postal, terminos)
                VALUES
                    (:id, :nombre, :direccion, :ciudad,
                     :estado, :pais, :codigo_postal, :terminos)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $this->id_tienda,
            ':nombre' => $this->nombre_tienda,
            ':direccion' => $this->direcc_tienda,
            ':ciudad' => $this->ciudad,
            ':estado' => $this->estado,
            ':pais' => $this->pais,
            ':codigo_postal' => $this->cod_postal,
            ':terminos' => $this->terminos
        ]);
    }

  
    public function update(string $id, array $datos): bool
    {
        $id = trim($id);

        if ($id === '' || strlen($id) > 4 || !$this->getById($id)) {
            return false;
        }

        if (!empty($this->validar($datos))) {
            return false;
        }

        $this->cargarDatos($datos);

        $sql = "UPDATE tiendas
                SET nombre_tienda = :nombre,
                    direcc_tienda = :direccion,
                    ciudad = :ciudad,
                    estado = :estado,
                    pais = :pais,
                    cod_postal = :codigo_postal,
                    terminos = :terminos
                WHERE id_tienda = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nombre' => $this->nombre_tienda,
            ':direccion' => $this->direcc_tienda,
            ':ciudad' => $this->ciudad,
            ':estado' => $this->estado,
            ':pais' => $this->pais,
            ':codigo_postal' => $this->cod_postal,
            ':terminos' => $this->terminos,
            ':id' => $id
        ]);
    }

    
    public function delete(string $id): bool
    {
        $id = trim($id);

        if ($id === '' || strlen($id) > 4) {
            return false;
        }

        $sql = "DELETE FROM tiendas WHERE id_tienda = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }
}
