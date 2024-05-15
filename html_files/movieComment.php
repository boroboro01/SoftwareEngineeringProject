<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Comment to Movie</title>
</head>

<body>
    <h1>Add Your Rating and Tag</h1>
    <form action="../php_files/movieCommentHandler.php" method="post">
        <?php
        $movieId = isset($_GET['movieId']) ? $_GET['movieId'] : 'default_id_or_error_handling';
        ?>

        <input type="text" id="movieId" name="movieId" value="<?php echo htmlspecialchars($movieId); ?>"
            readonly><br><br>

        <label for="rating">Rating (0.0 to 5.0):</label>
        <input type="number" id="rating" name="rating" step="0.1" min="0" max="5" required><br><br>

        <label for="tag">Tag:</label>
        <input type="text" id="tag" name="tag" required><br><br>

        <button type="submit">Submit Comment</button>
    </form>
</body>

</html>