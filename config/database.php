<?php

class Database
{
    private $host = "sql305.infinityfree.com";
    private $dbname = "if0_43508608_dblibreria";
    private $username = "if0_43508608";
    private $password = "Cramos96";
    private $conn;

    public function conectar()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";port=3306;dbname=" . $this->dbname . ";charset=utf8mb4",
                $this->username,
                $this->password
            );

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        } catch (PDOException $e) {
            error_log("Error de conexión MySQL: " . $e->getMessage());
            die("Error de conexion a la base de datos.");
        }

        return $this->conn;
    }
}