<form action="" method="post">
    <input type="hidden" name="action" value="add_author">
    <label for="author_id">Add Author:</label>
    <select name="author_id">
        <option value="">Select an author</option>
        <?php while ($author = $availableAuthorsStmt->fetch()) { ?>
            <option value="<?= $author['id']; ?>">
                <?= htmlspecialchars($author['first_name'] . ' ' . $author['last_name']); ?>
            </option>
        <?php } ?>
    </select>
    <button type="submit">Add Author</button>
</form>
