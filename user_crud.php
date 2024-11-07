<?php
include 'db.php';

// Create User
function createUser($name, $email, $phone, $address, $password) {
    global $conn;
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO Users (name, email, phone, address, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $phone, $address, $hashed_password);
    $stmt->execute();
    $stmt->close();
    return true;
}

// Read User
function getUser($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    return $user;
}

// Update User
function updateUser($user_id, $name, $email, $phone, $address, $password = null) {
    global $conn;
    if ($password) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE Users SET name = ?, email = ?, phone = ?, address = ?, password = ? WHERE user_id = ?");
        $stmt->bind_param("sssssi", $name, $email, $phone, $address, $hashed_password, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE Users SET name = ?, email = ?, phone = ?, address = ? WHERE user_id = ?");
        $stmt->bind_param("ssssi", $name, $email, $phone, $address, $user_id);
    }
    $stmt->execute();
    $stmt->close();
}

// Delete User
function deleteUser($user_id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM Users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        if (createUser($_POST['name'], $_POST['email'], $_POST['phone'], $_POST['address'], $_POST['password'])) {
            echo json_encode(['status' => 'success', 'message' => 'Registration successful!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Registration failed!']);
        }
        exit();
    } elseif (isset($_POST['update'])) {
        if (!empty($_POST['password'])) {
            updateUser($_POST['user_id'], $_POST['name'], $_POST['email'], $_POST['phone'], $_POST['address'], $_POST['password']);
        } else {
            updateUser($_POST['user_id'], $_POST['name'], $_POST['email'], $_POST['phone'], $_POST['address']);
        }
    } elseif (isset($_POST['delete'])) {
        deleteUser($_POST['user_id']);
    }
}
?>