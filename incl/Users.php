<?php
class User {
    private $conn;
    private $table = 'users';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Encrypt the username
    private function encryptUsername($username) {
        return hash('sha256', $username);
    }

    // Register a new user
    public function register($username, $password, $email, $image) {
        $query = "INSERT INTO $this->table (username, password, email, image) VALUES (:username, :password, :email, :image)";
        $stmt = $this->conn->prepare($query);

        $encryptedUsername = $this->encryptUsername($username);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt->bindParam(':username', $encryptedUsername);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':image', $image);

        return $stmt->execute();
    }

    public function login($username, $password) {
        $encryptedUsername = $this->encryptUsername($username);

        $query = "SELECT * FROM $this->table WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $encryptedUsername);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            if ($user['is_active'] == 1) {
                // User is active, proceed with login
                return $user;
            } else {
                // Account not activated
                return "Account not activated";
            }
        }
        return false;
    }

    // Edit user details
    public function editAccount($id, $newUsername, $newPassword, $newEmail, $newImage) {
        $query = "UPDATE $this->table SET username = :username, password = :password, email = :email, image = :image WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $encryptedUsername = $this->encryptUsername($newUsername);
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        $stmt->bindParam(':username', $encryptedUsername);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $newEmail);
        $stmt->bindParam(':image', $newImage);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // Delete user account
    public function deleteAccount($id) {
        $query = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // Fetch user details by ID
    public function getUserData($id) {
        $query = "SELECT * FROM $this->table WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
