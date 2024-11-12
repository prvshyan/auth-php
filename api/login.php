<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../config/database.php';
include_once '../models/User.php';
include_once '../utils/JWTHandler.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);
$jwt = new JWTHandler();

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->email) && !empty($data->password)) {
    $user->email = $data->email;

    if($user->emailExists() && password_verify($data->password, $user->password)) {
        $token = $jwt->generateToken($user->id, $user->email);

        http_response_code(200);
        echo json_encode([
            "message" => "Login successful",
            "status" => true,
            "token" => $token
        ]);
    } else {
        http_response_code(401);
        echo json_encode([
            "message" => "Invalid credentials",
            "status" => false
        ]);
    }
} else {
    http_response_code(400);
    echo json_encode([
        "message" => "unable to load",
        "status" => false
    ]);
}