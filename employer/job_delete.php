<?php
require '../db/db.php';

// Check if ID exists and User is Employer
if (isset($_GET['id']) && isset($_SESSION['user_id']) && $_SESSION['role'] == 'employer') {
    
    $job_id = (int)$_GET['id'];
    $employer_id = $_SESSION['user_id'];

    // Run Delete Query with Ownership Check
    $sql = "DELETE FROM jobs WHERE id = '$job_id' AND employer_id = '$employer_id'";

    if (mysqli_query($conn, $sql)) {
        
        header("Location: dashboard.php");
    } else {
        
        die("Error deleting record: " . mysqli_error($conn));
    }

} else {
    // Access Denied
    header("Location: index.php");
}
exit();
?>