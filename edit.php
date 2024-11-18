<?php
require_once('./connection.php');

$id = $_GET['id'];

// Fetch book data
$stmt = $pdo->prepare('SELECT * FROM books WHERE id = :id');
$stmt->execute(['id' => $id]);
$book = $stmt->fetch();

// Fetch book authors
$bookAuthorsStmt = $pdo->prepare('SELECT * FROM book_authors ba LEFT JOIN authors a ON ba.author_id=a.id WHERE ba.book_id = :id');
$bookAuthorsStmt->execute(['id' => $id]);

// Fetch available authors
$availableAuthorsStmt = $pdo->prepare('SELECT * FROM authors WHERE id NOT IN (SELECT author_id FROM book_authors WHERE book_id = :book_id)');
$availableAuthorsStmt->execute(['book_id' => $id]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        nav a {
            text-decoration: none;
            color: #007bff;
            margin-bottom: 20px;
            display: inline-block;
        }
        h3 {
            margin-bottom: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input[type="text"], select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            background-color: #f1f1f1;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .remove-btn {
            background: none;
            border: none;
            color: red;
            cursor: pointer;
        }
        .remove-btn svg {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<div class="container">
    <nav>
        <a href="./book.php?id=<?= $id; ?>">&larr; Back</a>
    </nav>

    <h3>Edit Book: <?= htmlspecialchars($book['title']); ?></h3>

    <form action="./update_book.php?id=<?= $id; ?>" method="post">
        <label for="title">Title:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($book['title']); ?>">

        <label for="price">Price:</label>
        <input type="text" name="price" value="<?= htmlspecialchars($book['price']); ?>">

        <button type="submit" name="action" value="save">Save</button>
    </form>

    <h3>Authors</h3>
    <ul>
        <?php while ($author = $bookAuthorsStmt->fetch()) { ?>
            <li>
                <?= htmlspecialchars($author['first_name'] . ' ' . $author['last_name']); ?>
                <form action="./remove_author.php?id=<?= $id; ?>" method="post" style="display: inline;">
                    <input type="hidden" name="author_id" value="<?= $author['id']; ?>">
                    <button type="submit" name="action" value="remove_author" class="remove-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" viewBox="0 0 24 24">
                            <path d="M 10.8 2 C 10.3 2 9.8 2.2 9.4 2.6 L 9 3 L 4 3 A 1 1 0 0 0 4 5 L 20 5 A 1 1 0 1 0 20 3 L 15 3 L 14.6 2.6 C 14.2 2.2 13.7 2 13.2 2 L 10.8 2 z M 4.4 7 L 5.9 20.3 C 6 21.3 6.9 22 7.9 22 L 16.1 22 C 17.1 22 18 21.3 18.1 20.3 L 19.6 7 L 4.4 7 z"></path>
                        </svg>
                    </button>
                </form>
            </li>
        <?php } ?>
    </ul>

    <form action="./add_author.php" method="post">
        <input type="hidden" name="book_id" value="<?= $id; ?>">
        <label for="author_id">Add Author:</label>
        <select name="author_id">
            <option value="">Select an author</option>
            <?php while ($author = $availableAuthorsStmt->fetch()) { ?>
                <option value="<?= $author['id']; ?>">
                    <?= htmlspecialchars($author['first_name'] . ' ' . $author['last_name']); ?>
                </option>
            <?php } ?>
        </select>
        <button type="submit" name="action" value="add_author">Add Author</button>
    </form>
</div>

</body>
</html>
