<?php
session_start();
include_once 'config.php';
include_once 'header.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = isset($_SESSION['role']) ? trim($_SESSION['role']) : '';

    if ($role === 'seeker') {
        // Insert seeker data into reg_user table
        $sql = $con->prepare("INSERT INTO reg_user(name, mobile, email, username, password) VALUES (?, ?, ?, ?, ?)");
        $sql->bind_param("sssss", $name, $mobile, $email, $username, $password);

        $name = $_POST['name'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($sql->execute()) {
            $_SESSION['msg'] = "Seeker signup successful!";
            header("location:login.php");
            exit();
        } else {
            $_SESSION['msg'] = "Error during seeker signup: " . $con->error;
        }
    } elseif ($role === 'recruiter') {
        // Check if company_id is unique
        $company_id = $_POST['company_id'];
        $check_sql = $con->prepare("SELECT company_id FROM employer WHERE company_id = ?");
        $check_sql->bind_param("s", $company_id);
        $check_sql->execute();
        $check_sql->store_result();

        if ($check_sql->num_rows > 0) {
            $_SESSION['msg'] = "Company ID already exists. Please use a unique Company ID.";
        } else {
            // Insert recruiter data into employer table
            $sql = $con->prepare("INSERT INTO employer(company_id, company_name, ceo, password) VALUES (?, ?, ?, ?)");
            $sql->bind_param("ssss", $company_id, $company_name, $ceo, $password);

            $company_name = $_POST['company_name'];
            $ceo = $_POST['ceo'];
            $password = $_POST['password'];

            if ($sql->execute()) {
                $_SESSION['msg'] = "Recruiter signup successful!";
                header("location:login.php");
                exit();
            } else {
                $_SESSION['msg'] = "Error during recruiter signup: " . $con->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="bootstrap.min.css" />
    <link rel="stylesheet" href="style.css" />
</head>
<body>
<section class="signup-banner">
    <h2>Signup</h2>
</section>

<section class="signup-portal">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 col-lg-push-4 col-md-push-4 col-sm-push-4 col-xs-push-3 signup-inner">
                <?php if (isset($_SESSION['msg'])): ?>
                    <div class="alert alert-warning">
                        <?php 
                            echo $_SESSION['msg']; 
                            unset($_SESSION['msg']);
                        ?>
                    </div>
                <?php endif; ?>

                <?php 
                $role = isset($_SESSION['role']) ? trim($_SESSION['role']) : '';
                if ($role === 'seeker'): 
                ?>
                    <!-- Seeker Signup Form -->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Your Name:</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Mobile Number:</label>
                            <input type="text" name="mobile" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email ID:</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Username:</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Password:</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary">Sign Up</button>
                        </div>
                    </form>
                <?php elseif ($role === 'recruiter'): ?>
                    <!-- Recruiter Signup Form -->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Company ID:</label>
                            <input type="text" name="company_id" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Company Name:</label>
                            <input type="text" name="company_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>CEO:</label>
                            <input type="text" name="ceo" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Password:</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary">Sign Up</button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-danger">
                        No role detected. Please login to start the signup process.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include_once 'footer.php'; ?>
</body>
</html>

