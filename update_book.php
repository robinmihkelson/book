<?php
require_once('./connection.php');

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'save') {
    $title = trim($_POST['title']);
    $price = trim($_POST['price']);

    if (!empty($title) && is_numeric($price)) {
        // Update the book
        $stmt = $pdo->prepare('UPDATE books SET title = :title, price = :price WHERE id = :id');
        $stmt->execute([
            'title' => $title,
            'price' => $price,
            'id' => $id
        ]);

        // Redirect back to edit.php
        header("Location: ./edit.php?id=$id");
        exit;
    } else {
        // Validation failed, redirect back to edit.php with an error
        header("Location: ./edit.php?id=$id&error=Invalid+data");
        exit;
    }
}
?>
