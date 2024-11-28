<?php
require 'Database.php';
require 'Crud.php';

$db = (new Database())->getConnection();
$crud = new Crud($db, "your_table");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Handle file uploads
    $image = null;
    $file = null;

    if (!empty($_FILES["image"]["name"])) {
        $image = 'uploads/' . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image);
    }

    if (!empty($_FILES["file"]["name"])) {
        $file = 'uploads/' . basename($_FILES["file"]["name"]);
        move_uploaded_file($_FILES["file"]["tmp_name"], $file);
    }

    // Create new record
    $crud->create([
        "name" => $_POST["name"],
        "gender" => $_POST["gender"],
        "category" => $_POST["category"],
        "dob" => $_POST["dob"],
        "image" => $image,
        "file" => $file
    ]);
}
?>

<form method="post" enctype="multipart/form-data">
    <label>Name: <input type="text" name="name" required></label><br>
    <label>Gender: 
        <input type="radio" name="gender" value="Male" required> Male
        <input type="radio" name="gender" value="Female" required> Female
    </label><br>
    <label>Category:
        <select name="category" required>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
        </select>
    </label><br>
    <label>Date of Birth: <input type="date" name="dob" required></label><br>
    <label>Image: <input type="file" name="image"></label><br>
    <label>File: <input type="file" name="file"></label><br>
    <button type="submit">Submit</button>
</form>
