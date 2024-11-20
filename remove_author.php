<ul>
    <?php while ($author = $bookAuthorsStmt->fetch()) { ?>
        <li>
            <?= htmlspecialchars($author['first_name'] . ' ' . $author['last_name']); ?>
            <form action="" method="post" style="display: inline;">
                <input type="hidden" name="action" value="remove_author">
                <input type="hidden" name="author_id" value="<?= $author['id']; ?>">
                <button type="submit" class="remove-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" viewBox="0 0 24 24">
                        <path d="M 10.8 2 C 10.3 2 9.8 2.2 9.4 2.6 L 9 3 L 4 3 A 1 1 0 0 0 4 5 L 20 5 A 1 1 0 1 0 20 3 L 15 3 L 14.6 2.6 C 14.2 2.2 13.7 2 13.2 2 L 10.8 2 z M 4.4 7 L 5.9 20.3 C 6 21.3 6.9 22 7.9 22 L 16.1 22 C 17.1 22 18 21.3 18.1 20.3 L 19.6 7 L 4.4 7 z"></path>
                    </svg>
                </button>
            </form>
        </li>
    <?php } ?>
</ul>
