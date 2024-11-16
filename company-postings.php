<?php
session_start();
include_once 'config.php';
include_once 'header.php';

// Get company ID from session
$c_id = isset($_SESSION['username']) ? $_SESSION['username'] : null;

if ($c_id === null) {
    echo "You need to log in as a recruiter.";
    exit;
}

// Fetch job postings for the company
$sql = $con->prepare("SELECT job_id, job_title, applied, posted FROM jobs WHERE company_id = ?");
$sql->bind_param("s", $c_id); // Assuming c_id is a string representing company_id
$sql->execute();
$result = $sql->get_result();

// Check if any jobs were found
if ($result->num_rows > 0) {
    // Display job postings
    echo "<h2>Your Job Postings</h2>";
    echo "<table class='table'>";
    echo "<tr><th>Job Title</th><th>Actions</th></tr>";

    while ($job = $result->fetch_assoc()) {
        $job_id = $job['job_id'];
        $job_title = htmlspecialchars($job['job_title']);
        $applied = $job['applied'];
        $posted = htmlspecialchars($job['posted']); // Escape for safety

        echo "<tr>";
        echo "<td>$job_title</td>";
        echo "<td>
                <a href='delete_job.php?job_id=$job_id' onclick='return confirm(\"Are you sure you want to delete this job posting?\");'>Delete</a> | 
                <a href='applied_candidates_view.php?job_id=$job_id'>View Applied Candidates</a>
              </td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<p>No job postings found for this company.</p>";
}

// Close statement and connection
$sql->close();
$con->close();
?>