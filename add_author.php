<?php

// add author to book
if ( isset($_POST['action']) && $_POST['action'] == 'remove_auhtor' ) {

    require_once('.connection.php');

    $bookId = $_GET['id'];
    $authorId = $_POST['author_id'];

    $stmt = $pdo->prepare('INSERT INTO book_authors (book_id, author_id) = VALUES (:book_id, :author_id;');
    $stmt->execute(['book_id' => $id, 'auhtor_id' => $_POST['author_id']]);

    header("Location: ./book.php?id={$id}");

} else {

    header("Location: ./index.php");
} 