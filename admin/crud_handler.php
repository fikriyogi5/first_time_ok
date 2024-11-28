<?php
require 'Database.php'; // Koneksi database
require 'Crud.php';     // Class CRUD

// Buat koneksi database
$db = (new Database())->getConnection();
$crud = new Crud($db, 'your_table'); // Ganti 'your_table' dengan nama tabel Anda

// Tangani request berdasarkan `action`
$action = isset($_GET['action']) ? $_GET['action'] : null;

switch ($action) {
    case 'create':
        // Handle Create
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $gender = $_POST['gender'];
            $category = $_POST['category'];
            $dob = $_POST['dob'];

            // Handle file upload for image
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imagePath = 'uploads/' . uniqid() . '_' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
            }

            // Handle file upload for other files
            $filePath = null;
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $filePath = 'uploads/' . uniqid() . '_' . $_FILES['file']['name'];
                move_uploaded_file($_FILES['file']['tmp_name'], $filePath);
            }

            $data = [
                'name' => $name,
                'gender' => $gender,
                'category' => $category,
                'dob' => $dob,
                'image' => $imagePath,
                'file' => $filePath,
                'status' => 'pending' // Default status for approval
            ];

            $result = $crud->create($data);

            echo json_encode(['success' => $result]);
        }
        break;

    case 'read':
        // Handle Read All
        $data = $crud->read();
        echo json_encode($data);
        break;

    case 'readOne':
        // Handle Read Single Record
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $data = $crud->readOne(['id' => $id]);
            echo json_encode($data);
        }
        break;

    case 'update':
        // Handle Update
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $gender = $_POST['gender'];
            $category = $_POST['category'];
            $dob = $_POST['dob'];

            // Handle file upload for image
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imagePath = 'uploads/' . uniqid() . '_' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
            }

            // Handle file upload for other files
            $filePath = null;
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $filePath = 'uploads/' . uniqid() . '_' . $_FILES['file']['name'];
                move_uploaded_file($_FILES['file']['tmp_name'], $filePath);
            }

            $data = [
                'name' => $name,
                'gender' => $gender,
                'category' => $category,
                'dob' => $dob,
                'image' => $imagePath,
                'file' => $filePath
            ];

            $result = $crud->update($data, ['id' => $id]);

            echo json_encode(['success' => $result]);
        }
        break;

    case 'delete':
        // Handle Delete
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $result = $crud->delete(['id' => $id]);
            echo json_encode(['success' => $result]);
        }
        break;

    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}
