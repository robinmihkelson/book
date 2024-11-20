<form action="./add_new_author.php" method="post" style="margin-top: 20px;">
    <h3>Add New Author</h3>
    <label for="first_name">First Name:</label>
    <input type="text" name="first_name" required placeholder="Enter first name">
    
    <label for="last_name">Last Name:</label>
    <input type="text" name="last_name" required placeholder="Enter last name">
    
    <button type="submit" name="action" value="add_new_author">Add New Author</button>
</form>

<?php
require_once('./connection.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'add_new_author') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];

    // Validate input
    if (!empty($firstName) && !empty($lastName)) {
        // Insert new author into the database
        $stmt = $pdo->prepare('INSERT INTO authors (first_name, last_name) VALUES (:first_name, :last_name)');
        $stmt->execute([
            'first_name' => $firstName,
            'last_name' => $lastName
        ]);
        
        // Redirect back to the edit book page
        header("Location: ./edit.php?id=" . $_GET['id']);
        exit;
    } else {
        echo "Please fill out both fields.";
    }
} else {
    echo "Invalid request.";
}
?>
