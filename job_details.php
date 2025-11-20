<?php 
require 'db/db.php'; 

// validate id
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$job_id = (int)$_GET['id'];
$message = "";
$msg_type = "";

// post method
if (isset($_POST['apply']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $cover_letter = mysqli_real_escape_string($conn, $_POST['cover_letter']);
    
    $sql = "INSERT INTO applications (job_id, user_id, cover_letter) VALUES ('$job_id', '$user_id', '$cover_letter')";
    
    try {
        if (mysqli_query($conn, $sql)) {
            $message = "Application submitted successfully!";
            $msg_type = "success";
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) { // Duplicate entry error code
            $message = "You have already applied for this job.";
            $msg_type = "warning";
        } else {
            $message = "Error: " . $e->getMessage();
            $msg_type = "danger";
        }
    }
}

// get method bookmark
if (isset($_GET['bookmark']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "INSERT INTO bookmarks (job_id, user_id) VALUES ('$job_id', '$user_id')";
    
    try {
        mysqli_query($conn, $sql);
        $message = "Job saved to bookmarks!";
        $msg_type = "success";
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            $message = "Job already bookmarked.";
            $msg_type = "warning";
        }
    }
}

// fetch job details
$sql = "SELECT * FROM jobs WHERE id = '$job_id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    header("Location: index.php"); 
    exit();
}

$job = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $job['title']; ?> - Job Finder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <?php if ($_SESSION['role'] == 'employer' && $_SESSION['user_id'] == $job['employer_id']): ?>
                    <a class="navbar-brand" href="index.php">Talent Finder</a>
                <?php else: ?>
                    <a class="navbar-brand" href="index.php">Job Finder</a>
                <?php endif; ?>  
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['role'] == 'employer'): ?>
                            <li class="nav-item"><a class="nav-link" href="./employer/dashboard.php">Dashboard</a></li>
                        <?php else: ?>
                            <li class="nav-item"><a class="nav-link" href="./jobseeker/applications.php">My Apps</a></li>
                            <li class="nav-item"><a class="nav-link" href="./jobseeker/saved.php">Bookmarks</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link btn btn-danger text-white btn-sm ms-2" href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        
        <?php if($message): ?>
            <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="bg-white p-5 rounded shadow-sm mb-4">
            <h1 class="fw-bold"><?php echo $job['title']; ?></h1>
            <h4 class="text-muted"><?php echo $job['company_name']; ?></h4>
            <div class="mt-3">
                <span class="badge bg-primary"><?php echo $job['job_type']; ?></span>
                <span class="badge bg-success">&#8377;<?php echo number_format($job['salary']); ?> / Month</span>
                <span class="badge bg-secondary"><?php echo $job['location']; ?></span>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Job Description</h5>
                    </div>
                    <div class="card-body">
                        <p style="white-space: pre-line;"><?php echo $job['description']; ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Take Action</h5>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            
                            <?php if ($_SESSION['role'] == 'seeker'): ?>
                                <form action="" method="POST">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Cover Letter</label>
                                        <textarea name="cover_letter" class="form-control" rows="4" placeholder="Why are you a good fit?" required></textarea>
                                    </div>
                                    <button type="submit" name="apply" class="btn btn-primary w-100 mb-2">Submit Application</button>
                                </form>

                                <a href="job_details.php?id=<?php echo $job['id']; ?>&bookmark=true" class="btn btn-outline-danger w-100"> Bookmark for Later</a>

                            <?php elseif ($_SESSION['role'] == 'employer' && $_SESSION['user_id'] == $job['employer_id']): ?>
                                <div class="d-grid gap-2">
                                    <a href="./employer/job_edit.php?id=<?php echo $job['id']; ?>" class="btn btn-warning">Edit Job</a>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">Logged in as Employer.</p>
                            <?php endif; ?>

                        <?php else: ?>
                            <div class="alert alert-warning text-center">
                                Please <a href="login.php">login</a> to apply.
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>