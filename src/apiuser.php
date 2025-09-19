<?php
header("Content-Type: application/json; charset=UTF-8");
require_once("conexaobd.php");
require_once("usuarios.php");

$db = (new Database())->getConnection();
$usuario = new Usuario($db);

$action = $_GET['action'] ?? '';

switch($action){
    case 'cadastrar':
        $data = json_decode(file_get_contents("php://input"));
        $usuario->nome = $data->nome;
        $usuario->email = $data->email;
        $usuario->senha = $data->senha;
        $usuario->eh_admin = $data->role == 'adm' ? 1 : 0;
        echo json_encode(["success" => $usuario->cadastrar()]);
        break;

    case 'login':
        $data = json_decode(file_get_contents("php://input"));
        $usuario->email = $data->email;
        $usuario->senha = $data->senha;
        $loggedInUser = $usuario->login();
        if ($loggedInUser) {
            echo json_encode(["success" => true, "usuario" => $loggedInUser]);
        } else {
            echo json_encode(["success" => false, "message" => "Email ou senha inválidos."]);
        }
        break;

    case 'listar':
        $stmt = $usuario->listar();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($usuarios);
        break;

    case 'deletar':
        $data = json_decode(file_get_contents("php://input"));
        $usuario->id = $data->id;
        echo json_encode(["success" => $usuario->deletar()]);
        break;

    default:
        echo json_encode(["error" => "Ação inválida"]);
}
?>