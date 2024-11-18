<?php

require_once('./connection.php');

$stmt = $pdo->query('SELECT * FROM books WHERE is_deleted = 0');

?>

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

<h1>Welcome to the Bookstore</h1>

<div class="book-list">
    <?php while ($book = $stmt->fetch()) { ?>
        <div class="book-item">
            <a class="book-title" href="./book.php?id=<?= $book['id']; ?>">
                <?= htmlspecialchars($book['title']); ?>
            </a>
            <p class="book-price"><?= round($book['price'], 2); ?> &euro;</p>
        </div>
    <?php } ?>
</div>

</body>
</html>