<?php
class Item {
    private $conn;
    private $table_name = "itens";

    public $id;
    public $nome;
    public $descricao;

    public function __construct($db){
        $this->conn = $db;
    }

    public function cadastrar(){
        $query = "INSERT INTO " . $this->table_name . " (nome, descricao) VALUES (:nome, :descricao)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":descricao", $this->descricao);
        return $stmt->execute();
    }

    public function listar(){
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function deletar(){
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }
}
