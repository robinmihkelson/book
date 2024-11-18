<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookstore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .search-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .search-container input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .search-container button {
            padding: 10px 15px;
            border: none;
            background-color: #007BFF;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-container button:hover {
            background-color: #0056b3;
        }
        .book-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .book-item {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 200px;
            padding: 15px;
            text-align: center;
            transition: transform 0.2s;
        }
        .book-item:hover {
            transform: translateY(-5px);
        }
        .book-title {
            font-size: 1.2em;
            color: #007BFF;
            text-decoration: none;
        }
        .book-title:hover {
            text-decoration: underline;
        }
        .book-price {
            margin-top: 10px;
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>
<body>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author_ids = $_POST['authors'];

    // Update book details
    $updateStmt = $pdo->prepare('UPDATE books SET title = :title WHERE id = :id');
    $updateStmt->execute(['title' => $title, 'id' => $id]);

    // Clear existing authors and add new ones
    $clearAuthorsStmt = $pdo->prepare('DELETE FROM book_authors WHERE book_id = :id');
    $clearAuthorsStmt->execute(['id' => $id]);

    foreach ($author_ids as $author_id) {
        $insertAuthorStmt = $pdo->prepare('INSERT INTO book_authors (book_id, author_id) VALUES (:book_id, :author_id)');
        $insertAuthorStmt->execute(['book_id' => $id, 'author_id' => $author_id]);
    }

    echo "<p>Book updated successfully!</p>";
}
?>
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