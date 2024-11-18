<?php

require_once('./connection.php');

// Check if a search query is present
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare the SQL statement with a search filter
$stmt = $pdo->prepare('SELECT * FROM books WHERE is_deleted = 0 AND title LIKE :searchQuery');
$stmt->execute(['searchQuery' => '%' . $searchQuery . '%']);

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

<h1>Welcome to the Bookstore</h1>

<div class="search-container">
    <form action="" method="GET">
        <input type="text" name="search" placeholder="Search for books..." value="<?= htmlspecialchars($searchQuery); ?>">
        <button type="submit">Search</button>
    </form>
</div>

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