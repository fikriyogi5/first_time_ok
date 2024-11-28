<?php
require 'Database.php';
require 'Crud.php';

$db = (new Database())->getConnection();
$crud = new Crud($db, "your_table");

if ($_GET['action'] === 'readPending') {
    $data = $crud->read(["status" => "pending"]);
    echo json_encode($data);
    exit;
}

if ($_GET['action'] === 'updateStatus') {
    $input = json_decode(file_get_contents("php://input"), true);
    $id = $input['id'];
    $status = $input['status'];

    $result = $crud->update(["status" => $status], ["id" => $id]);
    echo json_encode(["success" => $result]);
    exit;
}

