<?php 
require '../db/db.php'; 

// login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

//for employers
if ($role == 'employer') {
    
    // Check if Job ID is provided
    if (!isset($_GET['job_id'])) {
        header("Location: dashboard.php");
        exit();
    }
    $job_id = (int)$_GET['job_id'];

    // Verify Ownership (Security)
    $check_sql = "SELECT * FROM jobs WHERE id = '$job_id' AND employer_id = '$user_id'";
    $check_res = mysqli_query($conn, $check_sql);
    if (mysqli_num_rows($check_res) == 0) {
        die("Access Denied: You do not own this job posting.");
    }

    // Handle Status Updates (Accept/Reject)
    if (isset($_POST['update_status'])) {
        $app_id = (int)$_POST['app_id'];
        $new_status = mysqli_real_escape_string($conn, $_POST['status']);
        mysqli_query($conn, "UPDATE applications SET status = '$new_status' WHERE id = '$app_id'");
    }

    // Fetch Applicants
    // We join 'applications' with 'users' to get the applicant's name and email
    $sql = "SELECT applications.*, users.name, users.email 
            FROM applications 
            JOIN users ON applications.user_id = users.id 
            WHERE applications.job_id = '$job_id'";
    
    $result = mysqli_query($conn, $sql);
    $view_mode = 'employer';

// for users or jobseekers

} else {
    
    $sql = "SELECT applications.*, jobs.title, jobs.company_name, jobs.id as real_job_id
            FROM applications 
            JOIN jobs ON applications.job_id = jobs.id 
            WHERE applications.user_id = '$user_id' 
            ORDER BY applications.applied_at DESC";
    
    $result = mysqli_query($conn, $sql);
    $view_mode = 'seeker';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications - Job Finder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="../index.php">Job Finder</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                    <?php if($role == 'employer'): ?>
                        <li class="nav-item"><a class="nav-link" href="../employer/dashboard.php">Dashboard</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link active" href="applications.php">Applications</a></li>
                         <li class="nav-item"><a class="nav-link" href="./saved.php">Bookmarks</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white btn-sm ms-2" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        
        <?php if ($view_mode == 'employer'): ?>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Applicants for Job #<?php echo $job_id; ?></h2>
                <a href="../employer/dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Candidate Name</th>
                                    <th>Cover Letter</th>
                                    <th>Current Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo $row['name']; ?></strong><br>
                                            <small class="text-muted"><?php echo $row['email']; ?></small>
                                        </td>
                                        <td>
                                            <p class="small mb-0"><?php echo nl2br($row['cover_letter']); ?></p>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo ($row['status'] == 'shortlisted' ? 'success' : ($row['status'] == 'rejected' ? 'danger' : 'warning')); ?>">
                                                <?php echo ucfirst($row['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <form method="POST" style="display:inline-block;">
                                                <input type="hidden" name="app_id" value="<?php echo $row['id']; ?>">
                                                <input type="hidden" name="status" value="shortlisted">
                                                <button type="submit" name="update_status" class="btn btn-sm btn-success">Accept</button>
                                            </form>
                                            <form method="POST" style="display:inline-block;">
                                                <input type="hidden" name="app_id" value="<?php echo $row['id']; ?>">
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" name="update_status" class="btn btn-sm btn-danger">Reject</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-muted text-center py-3">No applicants yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        
        <?php else: ?>
            <h2>My Application History</h2>
            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Job Title</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Link</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo $row['title']; ?></strong><br>
                                            <small class="text-muted"><?php echo $row['company_name']; ?></small>
                                        </td>
                                        <td><?php echo date('M d', strtotime($row['applied_at'])); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo ($row['status'] == 'shortlisted' ? 'success' : ($row['status'] == 'rejected' ? 'danger' : 'warning')); ?>">
                                                <?php echo ucfirst($row['status']); ?>
                                            </span>
                                        </td>
                                        <td><a href="../job_details.php?id=<?php echo $row['real_job_id']; ?>" class="btn btn-sm btn-outline-primary">View</a></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-muted text-center py-3">You haven't applied to any jobs yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>