<?php
include 'includes/header.php';
include 'includes/db_connect.php';

if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($conn, $_POST['search']);
    $sql = "SELECT * FROM courses WHERE course_name LIKE '%$search%'";
    $result = mysqli_query($conn, $sql);

    echo "<h2>Search Results</h2>";
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='course'>";
            echo "<h3>" . htmlspecialchars($row['course_name']) . "</h3>";
            echo "<p>" . htmlspecialchars($row['course_description']) . "</p>";
            echo "</div>";
        }
    } else {
        echo "No results found.";
    }
}
?>

<form method="POST">
    <input type="text" name="search" placeholder="Search courses...">
    <button type="submit">Search</button>
</form>

<?php include 'includes/footer.php'; ?>
