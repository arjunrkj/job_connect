<?php
session_start();
include_once 'config.php';
include_once 'header.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $role = isset($_SESSION['role']) ? trim($_SESSION['role']) : '';

    if ($role === 'seeker') {
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $sql = "SELECT username, password FROM reg_user WHERE username='$user' AND password='$pass'";
        $result = $con->query($sql);

        if ($result->num_rows === 1) {
            $_SESSION['username'] = $user;
            header("location:user-index.php");
            exit();
        } else {
            $_SESSION['msg'] = "Incorrect username or password";
            header("location:login.php");
            exit();
        }
    } elseif ($role === 'recruiter') {
        $id = $_POST['c_id'];
        $pass = $_POST['password'];
        $sql = "SELECT ceo FROM employer WHERE company_id='$id' AND password='$pass'";
        $result = $con->query($sql);

        if ($result->num_rows === 1) {
            $_SESSION['username'] = $id;
            header("location:recruiter-index.php");
            exit();
        } else {
            $_SESSION['msg'] = "Incorrect company ID or password";
            header("location:login.php");
            exit();
        }
    }
}

// Check session role
$role = isset($_SESSION['role']) ? trim($_SESSION['role']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Add your CSS links here -->
</head>
<body>
    <section class="login-banner">
        <h2>Login</h2>
    </section>

    <section class="login-portal">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 col-lg-push-4 col-md-push-4 col-sm-push-4 col-xs-push-3 login-inner">
                    <?php if (isset($_SESSION['msg'])): ?>
                        <div class="alert alert-warning">
                            <?php 
                                echo $_SESSION['msg'];
                                unset($_SESSION['msg']); 
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($role === 'seeker'): ?>
                        <!-- Login form for seekers -->
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                    <?php elseif ($role === 'recruiter'): ?>
                        <!-- Login form for recruiters -->
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group">
                                <label for="c_id">Company ID:</label>
                                <input type="text" name="c_id" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-danger">
                            Role not recognized or session expired. Please try again.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php include_once 'footer.php'; ?>
</body>
</html>
