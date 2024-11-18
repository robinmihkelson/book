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
    <title>Modern Bookstore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        h1 {
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #333;
        }
        .search-container {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }
        .search-container input[type="text"] {
            padding: 10px 15px;
            width: 350px;
            border: 1px solid #ccc;
            border-radius: 25px;
            outline: none;
            transition: all 0.3s ease;
        }
        .search-container input[type="text"]:focus {
            border-color: #007bff;
        }
        .search-container button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            margin-left: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .search-container button:hover {
            background-color: #0056b3;
        }
        .book-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: auto;
        }
        .book-item {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .book-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .book-content {
            padding: 15px;
            text-align: center;
        }
        .book-title {
            display: block;
            font-size: 1.25em;
            margin-bottom: 10px;
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s;
        }
        .book-title:hover {
            color: #0056b3;
        }
        .book-price {
            font-size: 1.1em;
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>
<body>

<h1>Bookstore</h1>

<div class="search-container">
    <form action="" method="GET">
        <input type="text" name="search" placeholder="Search for books..." value="<?= htmlspecialchars($searchQuery); ?>">
        <button type="submit"><i class="fas fa-search"></i></button>
    </form>
</div>

<div class="book-list">
    <?php while ($book = $stmt->fetch()) { ?>
        <div class="book-item">
            <div class="book-content">
                <a class="book-title" href="./book.php?id=<?= $book['id']; ?>">
                    <?= htmlspecialchars($book['title']); ?>
                </a>
                <p class="book-price"><?= round($book['price'], 2); ?> &euro;</p>
            </div>
        </div>
    <?php } ?>
</div>

</body>
</html>
