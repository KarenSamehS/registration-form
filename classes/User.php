<?php
class User {
 private $conn;
 public $id, $fullname, $email, $gender, $hobbies, $country;

public function __construct($conn) {
        $this->conn = $conn;
    }
public function __destruct() {
    if ($this->conn) {
        $this->conn->close();
    }
}

public function loadById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($user = $result->fetch_assoc()) {
            $this->id = $user['id'];
            $this->fullname = $user['fullname'];
            $this->email = $user['email'];
            $this->gender = $user['gender'];
            $this->hobbies = $user['hobbies'];
            $this->country = $user['country'];
            return true;
        }
        return false;
    }
public function deleteUser($id) {
    $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
    if (!$stmt) {
        throw new Exception("Statement preparation failed: " . $this->conn->error);
    }
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
public function deleteAllUsers() {
    $stmt = $this->conn->prepare("DELETE FROM users");
    if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $this->conn->error);
    }
    return $stmt->execute();
}


public function register($fullname, $email, $password, $gender, $hobbies, $country) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $this->conn->prepare("INSERT INTO users (fullname, email, password, gender, hobbies, country)
                                  VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssssss", $fullname, $email, $hashed_password, $gender, $hobbies, $country);
        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception("Execution error: " . $stmt->error);
        }
    } else {
        throw new Exception("Statement preparation error: " . $this->conn->error);
    }
}

public function update() {
        $stmt = $this->conn->prepare("UPDATE users SET fullname = ?, email = ?, gender = ?, hobbies = ?, country = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $this->fullname, $this->email, $this->gender, $this->hobbies, $this->country, $this->id);
        return $stmt->execute();
    }
    public function getUserByEmail($email) {
    $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
    if (!$stmt) return false;

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    return $result->fetch_assoc();
  }
  
public function getAllUsers() {
    if ($this->conn) {
        $sql = "SELECT * FROM users";
        $result = $this->conn->query($sql);
        if (!$result) {
            throw new Exception("Query error: " . $this->conn->error);
        }
        return $result;
    } else {
        throw new Exception("Database connection not set.");
    }
}
}
?>