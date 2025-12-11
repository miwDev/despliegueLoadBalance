<?php

class VotosRepo
{
    private $conn;

    public function __construct($host, $user, $password, $database)
    {
        try {
            $this->conn = new PDO("mysql:host=db;dbname=linares_db1", $user, $password); // ponemos db porque es el nombre de host de
        } catch (PDOException $e) {                                                                                //la db en nuestro docker compose 
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
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function findAllNo()
    {
        $sql = "SELECT SUM(votos_no) as total FROM votos";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
}