<?php

// update book data
if ( isset($_POST['action']) && $_POST['action'] == 'Salvesta' ) {

    require_once('.connection.php');

    $id = $_GET['id'];

    $stmt = $pdo->prepare('UPDATE books SET title = :title, price = :price WHERE id = :id');
    $stmt->execute(['id' => $id, 'title' => $_POST['title'], 'price' => $_POST['price'] ]);

    header("Location: ./book.php?id={$id}");

} else {

    header("Location: ./index.php");
}