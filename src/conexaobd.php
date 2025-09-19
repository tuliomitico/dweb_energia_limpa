<?php
class Database {
    private $host = "gateway01.us-east-1.prod.aws.tidbcloud.com";
    private $db_name = "meu_sistema";
    private $username = "uEa4CZ59rmX4DZf.root";
    private $password = "8bcN1IDet4oojJwu";
    private $port = 4000;
    private $opts = array(
        PDO::MYSQL_ATTR_SSL_CA=>'isrgrootx1.pem',
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT=>true
    );
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name, 
                                   $this->username, 
                                   $this->password,
                                   $this->opts);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Erro de conexão: " . $exception->getMessage();
        }
        return $this->conn;
    }
}