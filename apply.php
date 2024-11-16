<?php 
include_once 'header.php';
include_once 'config.php';
session_start(); // Start session to access session variables

// Check if user is logged in and username is set in session
if (!isset($_SESSION['username'])) {
    echo "You need to log in to apply for jobs.";
    exit;
}

$username = $_SESSION['username'];

// Get user_id from reg_user table
$user_query = $con->prepare("SELECT user_id FROM reg_user WHERE username = ?");
$user_query->bind_param("s", $username);
$user_query->execute();
$user_result = $user_query->get_result();
$user_record = $user_result->fetch_assoc();
$user_id = $user_record['user_id'];

// Check if coming from job-list.php
if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Handle file upload for resume
        $resume_path = '';
        if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
            $resume_path = 'uploads/' . basename($_FILES['cv']['name']);
            if (!move_uploaded_file($_FILES['cv']['tmp_name'], $resume_path)) {
                echo "Error uploading the resume. Please try again.";
                exit;
            }
        }

        // Prepare to insert into applications table
        $sql = $con->prepare("INSERT INTO applications (job_id, user_id, applicant_name, email, skills, qualification, experience, contact_number, cv) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // Bind the parameters
        $sql->bind_param("iisssssss", $job_id, $user_id, $name, $email, $skills, $qualification, $experience, $contact, $resume_path);

        // Get form data
        $name = $_POST['name'];
        $email = $_POST['email'];
        $skills = $_POST['skills'];
        $qualification = $_POST['qualification'];
        $experience = $_POST['experience'];
        $contact = $_POST['contact'];

        // Execute insert query
        if ($sql->execute()) {
            echo "Application submitted successfully.";
            // Optionally update applied count in jobs table
            $con->query("UPDATE jobs SET applied = applied + 1 WHERE job_id = '$job_id'");
        } else {
            echo "Error: " . $sql->error;
        }
    }
} elseif (isset($_GET['source']) && $_GET['source'] == 'index') {
    // Coming from Post Your Profile page; insert into candidates table
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Handle file upload for CV
        $cv_path = '';
        if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
            $cv_path = 'uploads/' . basename($_FILES['cv']['name']);
            if (!move_uploaded_file($_FILES['cv']['tmp_name'], $cv_path)) {
                echo "Error uploading the CV. Please try again.";
                exit;
            }
        }

        // Prepare to insert into candidates table
        $sql = $con->prepare("INSERT INTO candidates (cad_name, skills, exp, qualification, email, contact, cv) VALUES (?, ?, ?, ?, ?, ?, ?)");

        // Bind parameters and execute
        $sql->bind_param("sssssss", $cad_name, $cad_skills, $cad_experience, $cad_qualification, $cad_email, $cad_contact, $cv_path);
        
        // Get form data for candidates
        $cad_name = $_POST['name'];
        $cad_email = $_POST['email'];
        $cad_skills = $_POST['skills'];
        $cad_qualification = $_POST['qualification'];
        $cad_experience = $_POST['experience'];
        $cad_contact = $_POST['contact'];

        if ($sql->execute()) {
            echo "Profile submitted successfully.";
            header("location:user-index.php");
        } else {
            echo "Error: " . $sql->error;
        }
    }
} else {
    echo "Invalid request.";
}
?>

<section class="body-section">
<div class="container">
<div class="row">
<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 col-lg-push-2 col-sm-push-2 col-md-push-2">
<h2 align="center">Apply for a job</h2><br />
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?job_id=' . htmlspecialchars($job_id); ?>" method="post" enctype="multipart/form-data">
<label class="control-label">Name:</label>
<input type="text" name="name" class="form-control" required /><br />
<label class="control-label">Email:</label>
<input type="email" name="email" class="form-control" required /><br />
<label class="control-label">Skills:</label>
<input type="text" name="skills" class="form-control" required /><br />
<label class="control-label">Qualification:</label>
<input type="text" name="qualification" class="form-control" required /><br />
<label class="control-label">Experience:</label>
<input type="text" name="experience" class="form-control" required /><br />
<label class="control-label">Contact Number:</label>
<input type="text" name="contact" class="form-control" required /><br />
<label class="control-label">Upload your Resume:</label>
<input type="file" name="cv" required /><br />
<input type="submit" value="Submit Application" class="thm-btn"/>
</form>
</div>
</div>
</div>
</section>

<?php 
include_once 'footer.php';
?>
