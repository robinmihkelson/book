<?php

// remove author from book
if ( isset($_POST['action']) && $_POST['action'] == 'remove_auhtor' ) {

    require_once('.connection.php');

    $id = $_GET['id'];

    $stmt = $pdo->prepare('DELETE FROM book_authors WHERE book_id = :book_id AND author_id = :author_id;');
    $stmt->execute(['book_id' => $id, 'auhtor_id' => $_POST['author_id']]);

    header("Location: ./book.php?id={$id}");

} else {

    header("Location: ./index.php");
} 
