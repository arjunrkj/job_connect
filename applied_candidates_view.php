<?php
session_start();
include_once 'config.php';
include_once 'header.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo "You need to log in.";
    exit;
}

// Check if job_id is provided in the URL
if (!isset($_GET['job_id'])) {
    echo "No job specified.";
    exit;
}

$job_id = $_GET['job_id'];

// Fetch applicants for the specified job ID
$sql = $con->prepare("SELECT applicant_name, email, skills, qualification, experience, contact_number, cv FROM applications WHERE job_id = ?");
$sql->bind_param("i", $job_id); // Assuming job_id is an integer
$sql->execute();
$result = $sql->get_result();

// Check if there are applicants
if ($result->num_rows > 0) {
    echo "<h2>Applicants for Job ID: $job_id</h2>";
    echo "<table class='table'>";
    echo "<tr>
            <th>Applicant Name</th>
            <th>Email</th>
            <th>Skills</th>
            <th>Qualification</th>
            <th>Experience</th>
            <th>Contact Number</th>
            <th>CV</th>
          </tr>";

    while ($applicant = $result->fetch_assoc()) {
        $applicant_name = htmlspecialchars($applicant['applicant_name']);
        $email = htmlspecialchars($applicant['email']);
        $skills = htmlspecialchars($applicant['skills']);
        $qualification = htmlspecialchars($applicant['qualification']);
        $experience = htmlspecialchars($applicant['experience']);
        $contact_number = htmlspecialchars($applicant['contact_number']);
        $cv_path = htmlspecialchars($applicant['cv']); // Assuming this is a path to the CV file

        echo "<tr>";
        echo "<td>$applicant_name</td>";
        echo "<td>$email</td>";
        echo "<td>$skills</td>";
        echo "<td>$qualification</td>";
        echo "<td>$experience</td>";
        echo "<td>$contact_number</td>";
        echo "<td><a href='$cv_path' target='_blank'>View CV</a></td>"; // Link to view CV
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<p>No applicants found for this job.</p>";
}

// Close statement and connection
$sql->close();
$con->close();
?>