<?php
// submit_feedback.php

session_start();

// Database configuration
$host = 'localhost';
$db   = 'house_of_nad';
$user = 'root';    // Update this as needed
$pass = '';
$charset = 'utf8mb4';

// DSN and PDO setup
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit;
}

// ROUTER LOGIC
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Case 1: Feedback form
    if (isset($_POST['name'], $_POST['email'], $_POST['message'])) {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $message = trim($_POST['message']);

        if (empty($name) || empty($email) || empty($message)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
            exit;
        }

        $sql = "INSERT INTO feedback (name, email, message, submitted_at) VALUES (?, ?, ?, NOW())";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([$name, $email, $message]);
            echo json_encode(['status' => 'success', 'message' => 'Feedback submitted successfully.']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to save feedback.']);
        }
        exit;
    }

    // Case 2: Order tracking
    if (isset($_POST['order_id'])) {
        $order_id = trim($_POST['order_id']);
        if (empty($order_id)) {
            echo "Order ID is required.";
            exit;
        }

        $sql = "SELECT status FROM orders WHERE order_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$order_id]);
        $result = $stmt->fetch();

        if ($result) {
            echo "Current status: " . htmlspecialchars($result['status']);
        } else {
            echo "Order not found.";
        }
        exit;
    }

    // Case 3: Add to cart
    if (isset($_POST['product_id'])) {
        $product_id = trim($_POST['product_id']);

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $_SESSION['cart'][] = $product_id;

        // Optionally redirect or return JSON
        header("Location: cart.php");
        exit;
    }

    // Unknown POST action
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Only POST requests allowed.']);
}
