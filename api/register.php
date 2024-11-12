<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
include_once '../config/database.php';
include_once '../models/User.php';
$database = new Database();
$db = $database->getConnection();
$user = new User($db);
$data = json_decode(file_get_contents("php://input"));
if(
    !empty($data->name) &&
    !empty($data->email) &&
    !empty($data->password)
) {
    $user->name = $data->name;
    $user->email = $data->email;
    $user->password = $data->password;
    if($user->create()) {
        http_response_code(201);
        echo json_encode([
            "message" => "User registered successfully",
            "status" => true
        ]);
    } else {
        http_response_code(400);
        echo json_encode([
            "message" => "Unable to register the user",
            "status" => false
        ]);
    }
} else {
    http_response_code(400);
    echo json_encode([
        "message" => "Unable to register the user. Data is incomplete",
        "status" => false
    ]);
}