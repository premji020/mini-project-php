<?php 
// Connect to the database
require 'db/db.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Finder</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { background-color: #f8f9fa; }
    </style>
</head>
<body>
    <!-- navbar or header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'employer'): ?>
            <a class="navbar-brand" href="index.php">Talent Finder</a>
            <?php else: ?>
             <a class="navbar-brand" href="index.php">Job Finder</a>   
             <?php endif; ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['role'] == 'employer'): ?>
                            
                            <li class="nav-item"><a class="nav-link" href="employer/dashboard.php">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="employer/job_create.php">Post a Job</a></li>
                        <?php else: ?>
                            <li class="nav-item"><a class="nav-link" href="jobseeker/applications.php">Applications</a></li>
                             <li class="nav-item"><a class="nav-link" href="./jobseeker/saved.php">Bookmarks</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link btn btn-danger text-white btn-sm ms-2" href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
        <!-- main page -->
    <div class="container mb-5">
        <div class="bg-white p-5 rounded shadow-sm text-center border">
            
            <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'employer'): ?>
                <h1 class="fw-bold text-primary">Talent Finder</h1>
            <p class="text-muted mb-4">Post your jobs. Find the perfect candidate.</p>
            <?php else: ?>
                <h1 class="fw-bold text-primary">Find Your Dream Job</h1>
            <p class="text-muted mb-4">Browse from thousands of jobs</p>
            <?php endif; ?>
            <form action="index.php" method="GET" class="row g-2 justify-content-center">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control form-control-lg" placeholder="Job title or keyword" value="<?php if(isset($_GET['search'])) echo htmlspecialchars($_GET['search']); ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-lg w-100">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="container">
        <h3 class="mb-4 pb-2 border-bottom">Latest Opportunities</h3>
        
        <div class="row">
            <?php
           
            $sql = "SELECT * FROM jobs WHERE 1=1";

            //searching
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $search = mysqli_real_escape_string($conn, $_GET['search']);
                $sql .= " AND (title LIKE '%$search%' OR company_name LIKE '%$search%' OR tags LIKE '%$search%')";
            }

            // Sorting
            $sql .= " ORDER BY id DESC";

            // Execute
            $result = mysqli_query($conn, $sql);

            // Result
            if (mysqli_num_rows($result) > 0):
                while ($job = mysqli_fetch_assoc($result)):
            ?>
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100 border-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="card-title text-dark fw-bold mb-0"><?php echo htmlspecialchars($job['title']); ?></h5>
                                    <span class="badge bg-info text-dark"><?php echo htmlspecialchars($job['job_type']); ?></span>
                                </div>
                                <h6 class="card-subtitle text-muted mb-3">
                                    <?php echo htmlspecialchars($job['company_name']); ?> &bull; <?php echo htmlspecialchars($job['location']); ?>
                                </h6>
                                <p class="card-text text-secondary">
                                    <strong>Salary (per month):</strong> &#8377;<?php echo number_format($job['salary']); ?>
                                </p>
                                <a href="job_details.php?id=<?php echo $job['id']; ?>" class="btn btn-outline-primary w-100 mt-2">View Details</a>
                            </div>
                        </div>
                    </div>
            <?php 
                endwhile;
            else: 
            ?>
                <div class="col-12 text-center py-5">
                    <h4 class="text-muted">No jobs found.</h4>
                </div>
            <?php endif; ?>
        </div>
    </div>
<!-- footer -->
    <footer class="bg-light text-center text-lg-start mt-5 py-4 border-top">
        <div class="container text-muted">
            &copy; <?php echo date("Y"); ?> Job/Talent Finder. Built with PHP & CSS Bootstrap.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>