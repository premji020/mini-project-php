<?php 
require '../db/db.php'; 

// login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seeker') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// delete bookmark
if (isset($_GET['remove_id'])) {
    $job_id_to_remove = (int)$_GET['remove_id'];
    $delete_sql = "DELETE FROM bookmarks WHERE job_id = '$job_id_to_remove' AND user_id = '$user_id'";
    mysqli_query($conn, $delete_sql);
    header("Location: saved.php"); // Reload to clear the URL
    exit();
}

// select or fetch from bookmarked jobs
$sql = "SELECT jobs.*, bookmarks.created_at as saved_at 
        FROM bookmarks 
        JOIN jobs ON bookmarks.job_id = jobs.id 
        WHERE bookmarks.user_id = '$user_id' 
        ORDER BY bookmarks.created_at DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookmarked Jobs - Job Finder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="../index.php">Job Finder</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="applications.php">Applications</a></li>
                    <li class="nav-item"><a class="nav-link active" href="saved.php">Bookmarks</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white btn-sm ms-2" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2 class="mb-4">My Bookmarked Jobs</h2>

        <div class="row">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($job = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h5 class="card-title text-primary"><?php echo $job['title']; ?></h5>
                                    <a href="saved.php?remove_id=<?php echo $job['id']; ?>" class="btn-close" onclick="return confirm('Remove from bookmarked?');"></a>
                                </div>
                                
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo $job['company_name']; ?></h6>
                                <p class="card-text">
                                    <span class="badge bg-info text-dark"><?php echo $job['job_type']; ?></span>
                                    <span class="badge bg-success">&#8377;<?php echo number_format($job['salary']); ?></span>
                                </p>
                                <p class="small text-muted">Bookmarked on: <?php echo date('M d, Y', strtotime($job['saved_at'])); ?></p>
                                
                                <a href="../job_details.php?id=<?php echo $job['id']; ?>" class="btn btn-outline-primary btn-sm w-100">View & Apply</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <h4 class="text-muted">No bookmarked jobs found.</h4>
                    <a href="../index.php" class="btn btn-primary mt-3">Browse Jobs</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>