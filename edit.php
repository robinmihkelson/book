<?php if (isset($_GET['error'])): ?>
    <div class="error"><?= htmlspecialchars($_GET['error']); ?></div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div class="error"><?= htmlspecialchars($error); ?></div>
<?php endif; ?>




<?php
require_once('./connection.php');

$id = $_GET['id'] ?? NULL;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'add_author') {
    $authorId = $_POST['author_id'] ?? null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'remove_author') {
        $authorId = $_POST['author_id'] ?? null;
    
        if (!empty($authorId)) {
            // Remove the author from the book
            $stmt = $pdo->prepare('DELETE FROM book_authors WHERE book_id = :book_id AND author_id = :author_id');
            $stmt->execute([
                'book_id' => $id,
                'author_id' => $authorId,
            ]);
    
            // Redirect to prevent re-submission
            header("Location: ./edit.php?id=$id");
            exit;
        } else {
            $error = "Invalid author selected for removal.";
        }
    }
    

    if (!empty($authorId)) {
        // Add the author to the book
        $stmt = $pdo->prepare('INSERT INTO book_authors (book_id, author_id) VALUES (:book_id, :author_id)');
        $stmt->execute([
            'book_id' => $id,
            'author_id' => $authorId,
        ]);

        // Redirect to prevent re-submission
        header("Location: ./edit.php?id=$id");
        exit;
    } else {
        $error = "Please select a valid author to add.";
    }
}


// Check if the "Add New Author" form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'add_new_author') {
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);

    if (!empty($firstName) && !empty($lastName)) {
        // Insert the new author into the authors table
        $stmt = $pdo->prepare('INSERT INTO authors (first_name, last_name) VALUES (:first_name, :last_name)');
        $stmt->execute([
            'first_name' => $firstName,
            'last_name' => $lastName
        ]);

        // Optional: Refresh the available authors list
        header("Location: ./edit.php?id=" . $id);
        exit;
    } else {
        $error = "Please provide both a first name and a last name.";
    }
}

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
        .error {
            color: red;
            margin-bottom: 15px;
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
    <input type="text" name="title" value="<?= htmlspecialchars($_POST['title'] ?? $book['title']); ?>">

    <label for="price">Price:</label>
    <input type="text" name="price" value="<?= htmlspecialchars($_POST['price'] ?? $book['price']); ?>">

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

    <form action="" method="post">
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

    <form action="" method="post" style="margin-top: 20px;">
        <h3>Add New Author</h3>
        <?php if (!empty($error)) { ?>
            <div class="error"><?= htmlspecialchars($error); ?></div>
        <?php } ?>
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" required placeholder="Enter first name">
        
        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" required placeholder="Enter last name">
        
        <button type="submit" name="action" value="add_new_author">Add New Author</button>
    </form>
</div>

</body>
</html>
