<?php

class VotosRepo
{
    private $conn;

    public function __construct($host, $user, $password, $database)
    {
        try {
            $this->conn = new PDO("mysql:host=db;dbname=linares_db1", $user, $password);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function saveVotoSi()
    {
        $sql = "INSERT INTO votos (votos_si, votos_no) VALUES (1, 0)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute();
    }

    public function saveVotoNo()
    {
        $sql = "INSERT INTO votos (votos_si, votos_no) VALUES (0, 1)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute();
    }

    public function findAllSi()
    {
        $sql = "SELECT SUM(votos_si) as total FROM votos";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }

    public function findAllNo()
    {
        $sql = "SELECT SUM(votos_no) as total FROM votos";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }
}

// Ejemplo de uso:
// $repo = new VotosRepository('db1', 'user', '1234', 'linares_db');
// $repo->saveVotoSi();
// echo "Votos SÍ: " . $repo->findAllSi();
// echo "Votos NO: " . $repo->findAllNo();
