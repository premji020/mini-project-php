<?php 
require '../db/db.php'; 

// login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employer' || !isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$job_id = (int)$_GET['id'];
$employer_id = $_SESSION['user_id'];

// Verify ownership and fetch current details
$sql = "SELECT * FROM jobs WHERE id = '$job_id' AND employer_id = '$employer_id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    die("Access Denied: You cannot edit this job.");
}

$job = mysqli_fetch_assoc($result);
$error = "";

// update db
if (isset($_POST['update_job'])) {
    $title       = mysqli_real_escape_string($conn, $_POST['title']);
    $company     = mysqli_real_escape_string($conn, $_POST['company']);
    $location    = mysqli_real_escape_string($conn, $_POST['location']);
    $salary      = (int)$_POST['salary'];
    $type        = mysqli_real_escape_string($conn, $_POST['type']);
    $tags        = mysqli_real_escape_string($conn, $_POST['tags']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $update_sql = "UPDATE jobs SET 
                   title='$title', company_name='$company', location='$location', 
                   salary='$salary', job_type='$type', tags='$tags', description='$description' 
                   WHERE id='$job_id' AND employer_id='$employer_id'";

    if (mysqli_query($conn, $update_sql)) {
        header("Location: dashboard.php"); // Redirect on success
        exit();
    } else {
        $error = "Error updating job: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Job - Talent Finder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="../index.php">Talent Finder</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">Edit Job: <?php echo $job['title']; ?></h4>
                    </div>
                    <div class="card-body p-4">
                        
                        <?php if($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Job Title</label>
                                    <input type="text" name="title" class="form-control" value="<?php echo $job['title']; ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Company Name</label>
                                    <input type="text" name="company" class="form-control" value="<?php echo $job['company_name']; ?>" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Location</label>
                                    <input type="text" name="location" class="form-control" value="<?php echo $job['location']; ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Salary in &#8377; (per month)</label>
                                    <input type="number" name="salary" class="form-control" value="<?php echo $job['salary']; ?>" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Job Type</label>
                                    <select name="type" class="form-select">
                                        <option value="Full Time" <?php if($job['job_type']=='Full Time') echo 'selected'; ?>>Full Time</option>
                                        <option value="Part Time" <?php if($job['job_type']=='Part Time') echo 'selected'; ?>>Part Time</option>
                                        <option value="Contract" <?php if($job['job_type']=='Contract') echo 'selected'; ?>>Contract</option>
                                        <option value="Freelance" <?php if($job['job_type']=='Freelance') echo 'selected'; ?>>Freelance</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tags</label>
                                    <input type="text" name="tags" class="form-control" value="<?php echo $job['tags']; ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="5" required><?php echo $job['description']; ?></textarea>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" name="update_job" class="btn btn-warning btn-lg">Update Job</button>
                                <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>