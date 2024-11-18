<?php

require_once('./connection.php');

$id = $_GET['id'];

$stmt = $pdo->prepare('SELECT * FROM books WHERE id = :id');
$stmt->execute(['id' => $id]);
$book = $stmt->fetch();

$stmt = $pdo->prepare('SELECT * FROM book_authors ba LEFT JOIN authors a ON ba.author_id=a.id WHERE ba.book_id = :id');
$stmt->execute(['id' => $id]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($book['title']); ?> - Book Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .book-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 600px;
            margin: auto;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .author-list {
            margin: 20px 0;
            list-style-type: none;
            padding: 0;
        }
        .author-list li {
            margin: 5px 0;
        }
        .book-price {
            font-size: 1.5em;
            font-weight: bold;
            color: #28a745;
            text-align: center;
        }
        .button {
            display: inline-block;
            padding: 10px 15px;
            margin: 10px 0;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .delete-form {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="book-container">
    <h1><?= htmlspecialchars($book['title']); ?></h1>

    <h3>Autorid:</h3>
    <ul class="author-list">
        <?php while ($author = $stmt->fetch()) { ?>
            <li>
                <?= htmlspecialchars($author['first_name']); ?> <?= htmlspecialchars($author['last_name']); ?>
            </li>
        <?php } ?>
    </ul>

    <p class="book-price"><?= round($book['price'], 2); ?> &euro;</p>

    <div class="button-container">
        <a class="button" href="./edit.php?id=<?= $id; ?>">Muuda</a>
    </div>

    <div class="delete-form">
        <form action="./delete.php" method="post">
            <input type="hidden" name="id" value="<?= $id; ?>">
            <input type="submit" name="action" value="Kustuta" class="button" style="background-color: #dc3545;">
        </form>
    </div>
</div>

</body>
</html>