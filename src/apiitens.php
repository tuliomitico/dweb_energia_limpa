<?php
header("Content-Type: application/json; charset=UTF-8");
require_once("conexaobd.php");
require_once("itens.php");

$db = (new Database())->getConnection();
$item = new Item($db);

$action = $_GET['action'] ?? '';

switch($action){
    case 'cadastrar':
        $data = json_decode(file_get_contents("php://input"));
        $item->nome = $data->nome;
        $item->descricao = $data->descricao;
        echo json_encode(["success" => $item->cadastrar()]);
        break;

    case 'listar':
        $stmt = $item->listar();
        $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($itens);
        break;

    case 'deletar':
        $data = json_decode(file_get_contents("php://input"));
        $item->id = $data->id;
        echo json_encode(["success" => $item->deletar()]);
        break;

    default:
        echo json_encode(["error" => "Ação inválida"]);
}
?>