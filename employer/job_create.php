<?php 
require '../db/db.php'; 

// login 
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employer') {
    header("Location: login.php");
    exit();
}

// form submission
$error = "";
if (isset($_POST['submit_job'])) {
    // Get data from form
    $employer_id = $_SESSION['user_id'];
    $title       = mysqli_real_escape_string($conn, $_POST['title']);
    $company     = mysqli_real_escape_string($conn, $_POST['company']);
    $location    = mysqli_real_escape_string($conn, $_POST['location']);
    $salary      = (int)$_POST['salary']; // Force to integer
    $type        = mysqli_real_escape_string($conn, $_POST['type']);
    $tags        = mysqli_real_escape_string($conn, $_POST['tags']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Insert Query
    $sql = "INSERT INTO jobs (employer_id, title, company_name, location, job_type, salary, tags, description) 
            VALUES ('$employer_id', '$title', '$company', '$location', '$type', '$salary', '$tags', '$description')";

    if (mysqli_query($conn, $sql)) {
        header("Location: dashboard.php"); // Success -> Go to Dashboard
        exit();
    } else {
        $error = "Error posting job: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Job - Talent Finder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="../index.php">Talent Finder</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="job_create.php">Post a Job</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white btn-sm ms-2" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Post a New Job</h4>
                    </div>
                    <div class="card-body p-4">
                        
                        <?php if($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Job Title</label>
                                    <input type="text" name="title" class="form-control" placeholder="e.g. Senior PHP Dev" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Company Name</label>
                                    <input type="text" name="company" class="form-control" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Location</label>
                                    <input type="text" name="location" class="form-control" placeholder="e.g. New York / Remote" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Salary (Monthly &#8377;)</label>
                                    <input type="number" name="salary" class="form-control" placeholder="e.g. &#8377;60000" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Job Type</label>
                                    <select name="type" class="form-select">
                                        <option value="Full Time">Full Time</option>
                                        <option value="Part Time">Part Time</option>
                                        <option value="Contract">Contract</option>
                                        <option value="Freelance">Freelance</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tags (Comma Separated)</label>
                                    <input type="text" name="tags" class="form-control" placeholder="php, sql, bootstrap">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Job Description</label>
                                <textarea name="description" class="form-control" rows="5" required></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" name="submit_job" class="btn btn-primary btn-lg">Post Job</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>