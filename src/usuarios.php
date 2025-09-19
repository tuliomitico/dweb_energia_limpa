<?php
class Usuario {
    private $conn;
    private $table_name = "usuarios";
    public $id;
    public $nome;
    public $email;
    public $senha;
    public $eh_admin;
    public function __construct($db){
        $this->conn = $db;
    }

    public function cadastrar(){
        $query = "INSERT INTO " . $this->table_name . " (nome, email, senha, eh_admin) VALUES (:nome, :email, :senha, :eh_admin)";
        $stmt = $this->conn->prepare($query);
        $this->senha = password_hash($this->senha, PASSWORD_BCRYPT);
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":senha", $this->senha);
        $stmt->bindParam(":eh_admin", $this->eh_admin);
        return $stmt->execute();
    }

    public function login(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if($usuario && password_verify($this->senha, $usuario['senha'])){
            return $usuario;
        }
        return false;
    }

    public function listar(){
        $query = "SELECT id, nome, email FROM " . $this->table_name;
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
