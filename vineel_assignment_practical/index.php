<?php

ob_start();

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "vineel_movie_4266";

// Enable PHP error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// CREATE new movie logic
if (isset($_POST['create'])) {
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $releaseDate = $_POST['releaseDate'];
    $rating = $_POST['rating'];
    $addedBy = 'Vineel Bheemanadham';  
    $stmt = $conn->prepare("INSERT INTO movies (Title, Genre, ReleaseDate, Rating, AddedBy) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssds", $title, $genre, $releaseDate, $rating, $addedBy);
    if ($stmt->execute()) {
        echo '<div class="alert alert-success">New movie added successfully</div>';
    } else {
        echo '<div class="alert alert-danger">Error adding movie: ' . $stmt->error . '</div>';
    }
    $stmt->close();
}

// DELETE movie logic
if (isset($_GET['delete'])) {
    $movieID = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM movies WHERE MovieID=?");
    $stmt->bind_param("i", $movieID);
    
    if ($stmt->execute()) {
        echo '<div class="alert alert-success">Movie deleted successfully</div>';
    } else {
        echo '<div class="alert alert-danger">Error deleting movie: ' . $stmt->error . '</div>';
    }
    
    $stmt->close();
    header("Location: index.php");  
    exit();
}

// UPDATE movie logic
if (isset($_POST['update'])) {
    $movieID = $_POST['movieID'];
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $releaseDate = $_POST['releaseDate'];
    $rating = $_POST['rating'];

    $stmt = $conn->prepare("UPDATE movies SET Title=?, Genre=?, ReleaseDate=?, Rating=? WHERE MovieID=?");
    $stmt->bind_param("sssdi", $title, $genre, $releaseDate, $rating, $movieID);
    if ($stmt->execute()) {
        echo '<div class="alert alert-success">Movie updated successfully</div>';
    } else {
        echo '<div class="alert alert-danger">Error updating movie: ' . $stmt->error . '</div>';
    }
    $stmt->close();
}

// SELECT movies for display
$sql = "SELECT * FROM movies";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Movie List</h2>

        <!-- Create Movie Form -->
        <h3>Add New Movie</h3>
        <form method="post" action="" class="mb-4">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" name="title" required>
            </div>
            <div class="mb-3">
                <label for="genre" class="form-label">Genre</label>
                <input type="text" class="form-control" name="genre" required>
            </div>
            <div class="mb-3">
                <label for="releaseDate" class="form-label">Release Date</label>
                <input type="date" class="form-control" name="releaseDate" required>
            </div>
            <div class="mb-3">
                <label for="rating" class="form-label">Rating</label>
                <input type="number" step="0.1" class="form-control" name="rating" required>
            </div>
            <button type="submit" class="btn btn-success" name="create">Add Movie</button>
        </form>

        <!-- Display Movies Table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Movie ID</th>
                    <th>Title</th>
                    <th>Genre</th>
                    <th>Release Date</th>
                    <th>Rating</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['MovieID']; ?></td>
                        <td><?= $row['Title']; ?></td>
                        <td><?= $row['Genre']; ?></td>
                        <td><?= $row['ReleaseDate']; ?></td>
                        <td><?= $row['Rating']; ?></td>
                        <td>
                           
                            <a href="index.php?delete=<?= $row['MovieID']; ?>" class="btn btn-danger btn-sm">Delete</a>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#updateModal<?= $row['MovieID']; ?>">Update</button>
                        </td>
                    </tr>

                    <!-- Update Modal -->
                    <div class="modal fade" id="updateModal<?= $row['MovieID']; ?>" tabindex="-1" aria-labelledby="updateModalLabel<?= $row['MovieID']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="updateModalLabel<?= $row['MovieID']; ?>">Update Movie</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="post" action="">
                                        <input type="hidden" name="movieID" value="<?= $row['MovieID']; ?>">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">Title</label>
                                            <input type="text" class="form-control" name="title" value="<?= $row['Title']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="genre" class="form-label">Genre</label>
                                            <input type="text" class="form-control" name="genre" value="<?= $row['Genre']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="releaseDate" class="form-label">Release Date</label>
                                            <input type="date" class="form-control" name="releaseDate" value="<?= $row['ReleaseDate']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="rating" class="form-label">Rating</label>
                                            <input type="number" step="0.1" class="form-control" name="rating" value="<?= $row['Rating']; ?>" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary" name="update">Update</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
