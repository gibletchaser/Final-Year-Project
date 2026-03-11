<?php

session_start();

ini_set('display_errors',0);
error_reporting(0);

header('Content-Type: application/json');

require 'db.php';

function jsonExit($data,$status=200){
    http_response_code($status);
    echo json_encode($data);
    exit;
}

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    jsonExit(["success"=>false,"error"=>"Invalid request"],405);
}

$input = json_decode(file_get_contents("php://input"),true);

if(!$input){
    jsonExit(["success"=>false,"error"=>"Invalid JSON"]);
}

$action = $input['action'] ?? '';

switch($action){

    case "validate_cart":

        $cart = $input['cart'] ?? [];

        if(empty($cart)){
            jsonExit([
                "success"=>false,
                "error"=>"Cart empty"
            ]);
        }

        $validated = [];

        foreach($cart as $item){

            $menu_id = intval($item['id'] ?? 0);
            $qty = intval($item['quantity'] ?? 1);

            if($menu_id <=0 || $qty <=0) continue;

            $stmt = $conn->prepare("SELECT id,name,price FROM menu WHERE id=?");
            $stmt->bind_param("i",$menu_id);
            $stmt->execute();

            $res = $stmt->get_result();

            if($row = $res->fetch_assoc()){

                $validated[] = [
                    "id"=>$row['id'],
                    "name"=>$row['name'],
                    "price"=>$row['price'],
                    "quantity"=>$qty
                ];
            }
        }

        if(empty($validated)){
            jsonExit([
                "success"=>false,
                "error"=>"No valid items"
            ]);
        }

        jsonExit([
            "success"=>true,
            "cart"=>$validated
        ]);

    break;

    default:
        jsonExit([
            "success"=>false,
            "error"=>"Unknown action"
        ]);
}
