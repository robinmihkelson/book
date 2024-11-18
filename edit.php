<?php

require_once('./connection.php');

$id = $_GET['id'];

// get book data
$stmt = $pdo->prepare('SELECT * FROM books WHERE id = :id');
$stmt->execute(['id' => $id]);
$book = $stmt->fetch();

// get book auhtors
$bookAuthorsStmt = $pdo->prepare('SELECT * FROM book_authors ba LEFT JOIN authors a ON ba.author_id=a.id WHERE ba.book_id = :id');
$bookAuthorsStmt->execute(['id' => $id]);

// get available auhtors
$availableAuthorsStmt = $pdo->prepare('SELECT * FROM authors WHERE id NOT IN (SELECT author_id FROM book_authors WHERE book_id = :book_id)');
$availableAuthorsStmt->execute(['book_id' => $id]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <nav>
        <a href="./book.php?id=<?= $id; ?>">Tagasi</a>
    </nav>
    <br>

    <h3><?= $book['title'];?></h3>

    <form action="./update_book.php?id=<?= $id; ?>" method="post">
        <label for="title">Pealkiri:</label>
        <input type="text" name="title" value="<?= $book['title'];?>">
        <br>
        <label for="price">Hind:</label>
        <input type="text" name="price" value="<?= $book['price'];?>">
        <br><br>
        <input type="submit" name="action" value="Salvesta">
    </form>
   
<br><br>

    <h3>Autorid:</h3>

    <ul>
        <?php while ( $author = $bookAuthorsStmt->fetch() ) { ?>
            
            <li>
                <form action="./remove_author.php?id=<?= $id; ?>" method="post">
                    <?= $author['first_name']; ?>
                    <?= $author['last_name']; ?>
                    <button type="submit" name="action" value="remove_auhtor" style="cursor: pointer; border: 0; background-color: inherit; margin-left: 16px;">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="16px" height="16px" viewBox="0 0 24 24" style="vertical-align: text-top;">
                            <path d="M 10.806641 2 C 10.289641 2 9.7956875 2.2043125 9.4296875 2.5703125 L 9 3 L 4 3 A 1.0001 1.0001 0 1 0 4 5 L 20 5 A 1.0001 1.0001 0 1 0 20 3 L 15 3 L 14.570312 2.5703125 C 14.205312 2.2043125 13.710359 2 13.193359 2 L 10.806641 2 z M 4.3652344 7 L 5.8925781 20.263672 C 6.0245781 21.253672 6.877 22 7.875 22 L 16.123047 22 C 17.121047 22 17.974422 21.254859 18.107422 20.255859 L 19.634766 7 L 4.3652344 7 z"></path>
                        </svg>
                    </button>
                    <input type="hidden" name="author_id" value="<?= $author['id']; ?>">
                </form>
            </li>
        
        <?php } ?>
    </ul>

    <form action="./add_author.php" method="post">

        <input type="hidden" name="book_id" value="<?= $id; ?>">

        <select name="author_id">
    
            <option value=""></option>
        
        <?php while ( $author = $availableAuthorsStmt->fetch() ) { ?>
            <option value="<?= $author['id']; ?>">
                <?= $author['first_name']; ?>
                <?= $author['last_name']; ?>
            </option>
        <?php } ?>

        </select>

        <button type="submit" name="action" value="add_author">
            Lisa autor
        </button>

    </form>

</body>
</html>