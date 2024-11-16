<?php
session_start();
include_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['role'])) {
    $_SESSION['role'] = $_POST['role'];
    header('Location: index.php'); // Refresh page after setting role
    exit();
}

$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Job Dairy</title>
<link rel="stylesheet" href="bootstrap.min.css" />
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
<link rel="icon" href="images/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
<link rel="stylesheet" href="style.css" />
<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<!-- Role Selection Section -->
<?php if (!$role): ?>
    <div class="container text-center" style="padding: 50px;">
        <h2>Welcome to Job Dairy!</h2>
        <p>Please select your role to continue:</p>
        <form action="index.php" method="POST">
            <button type="submit" name="role" value="seeker" class="btn btn-primary">I'm looking for Jobs</button>
            <button type="submit" name="role" value="recruiter" class="btn btn-success">I'm a Recruiter</button>
        </form>
    </div>
<?php else: ?>

<?php include_once 'header.php'; ?>

<!-- Seeker Content -->
<?php if ($role === 'seeker'): ?>
<section>
    <div class="container-fluid">
        <div class="row">
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-content">
                    <h1>Your Future Begins Here</h1>
                    <p>More than 1000+ Jobs Available</p>
                    <div class="col-lg-10 col-lg-push-1">
                        <form action="job-list.php" method="get">
                            <div class="form-group">
                                <div class="col-lg-8">
                                    <div class="row">
                                        <input type="text" class="form-control" name="search" placeholder="Looking for a Job" style="border:none; border-radius: 10px 0px 0px 10px;"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-4">
                                    <div class="row">
                                        <select name="location" class="form-control" style="border:none; border-radius: 0px 10px 10px 0px;">
                                            <option>All Locations</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-4 col-lg-push-5">
                                    <div class="row">
                                        <input type="submit" class="slider-btn" value="Search" />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="carousel-inner">
                    <div class="item active"><img src="images/header_background.jpg"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- jobs by category starts here -->
<section class="category">
<div class="container">
<div class="row">
<h1>Find jobs by category</h1>
<div class="col-lg-4 category-content">
<span class="glyphicon glyphicon-briefcase"></span><br />
<a href="job-list.php?search=software">Software</a>
<p>Explore software industry jobs.</p>
</div>
<div class="col-lg-4 category-content">
<span class="glyphicon glyphicon-wrench"></span><br />
<a href="job-list.php?search=mechanical">Mechanical</a>
<p>Jobs in mechanical fields.</p>
</div>
<div class="col-lg-4 category-content">
<span class="glyphicon glyphicon-heart"></span><br />
<a href="job-list.php?search=customer-service">Customer Service</a>
<p>Customer service opportunities.</p>
</div>
</div>
<div class="row">
<div class="col-lg-4 category-content">
<span class="glyphicon glyphicon-credit-card"></span><br />
<a href="job-list.php?search=accounting">Accounting</a>
<p>Opportunities in accounting.</p>
</div>
<div class="col-lg-4 category-content">
<span class="glyphicon glyphicon-education"></span><br />
<a href="job-list.php?search=education">Education</a>
<p>Explore education jobs.</p>
</div>
<div class="col-lg-4 category-content">
<span class="glyphicon glyphicon-usd"></span><br />
<a href="job-list.php?search=marketing">Marketing</a>
<p>Marketing roles available.</p>
</div>
</div>
<div class="row">
<div class="col-lg-4 col-lg-push-5">
<a href="job-list.php" class="thm-btn">Browse all categories</a>
</div>
</div>
</div>
</div>
</section>

<!-- recently posted starts here -->

<section class="recent">
<div class="container">
<h1>Recently Posted</h1>
<?php
$curr_date=date("d-m-Y");
$date= $curr_date-4;
$year=date('Y');
$month=date('m');
$str=$year.'-'.$month.'-'.$date.'%';
$date=$date+1;
$str1=$year.'-'.$month.'-'.$date.'%';
$date=$date+1;
$str2=$year.'-'.$month.'-'.$date.'%';
$date=$date+1;
$str3=$year.'-'.$month.'-'.$date.'%';
$date=$date+1;
$str4=$year.'-'.$month.'-'.$date.'%';
$sql="select * from jobs where posted like '$str' or posted like '$str1' or posted like '$str2' or posted like '$str3' or posted like '$str4' order by posted DESC";
$result=$con->query($sql);
while($record=$result->fetch_assoc())
{
?>
<div class="row recent-jobs" >
<div class="col-lg-4 rj-title">
<h4><?php echo $record['job_title']; ?></h4>
</div>
<div class="col-lg-2 rj-location">
<span class="glyphicon glyphicon-map-marker"> <h4>location</h4></span>
</div>
<div class="col-lg-2 rj-posted">
<span class="glyphicon glyphicon-time"> <h4><?php $posted=date_create($record['posted']); echo date_format($posted,"F d"); ?></h4></span>
</div>
<div class="col-lg-4">
<a href="" class="thm-btn">view details</a>
</div>
</div>
<?php } ?>
</div>
</div>
</div>
</section>

<!-- Recruiter Content -->
<?php elseif ($role === 'recruiter'): ?>
<section>
    <div class="container-fluid">
        <div class="row">
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-content">
                    <h1>Your Perfect Candidate Awaits</h1>
                    <p>Find the Right Talent For Your Company</p>
                    <div class="col-lg-10 col-lg-push-1">
                        <form action="job-list.php" method="get">
                            <div class="form-group">
                                <div class="col-lg-8">
                                    <div class="row">
                                        <input type="text" class="form-control" name="search" placeholder="Search by Skills/Experience" style="border:none; border-radius: 10px 0px 0px 10px;"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-4">
                                    <div class="row">
                                        <select name="location" class="form-control" style="border:none; border-radius: 0px 10px 10px 0px;">
                                            <option>All Locations</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-4 col-lg-push-5">
                                    <div class="row">
                                        <input type="submit" class="slider-btn" value="Search" />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="carousel-inner">
                    <div class="item active"><img src="images/header_background.jpg"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- jobs by category starts here -->
<section class="category">
<div class="container">
<div class="row">
<h1>Find jobs by category</h1>
<div class="col-lg-4 category-content">
<span class="glyphicon glyphicon-briefcase"></span><br />
<a href="job-list.php?search=software">Software</a>
<p>Explore software industry jobs.</p>
</div>
<div class="col-lg-4 category-content">
<span class="glyphicon glyphicon-wrench"></span><br />
<a href="job-list.php?search=mechanical">Mechanical</a>
<p>Jobs in mechanical fields.</p>
</div>
<div class="col-lg-4 category-content">
<span class="glyphicon glyphicon-heart"></span><br />
<a href="job-list.php?search=customer-service">Customer Service</a>
<p>Customer service opportunities.</p>
</div>
</div>
<div class="row">
<div class="col-lg-4 category-content">
<span class="glyphicon glyphicon-credit-card"></span><br />
<a href="job-list.php?search=accounting">Accounting</a>
<p>Opportunities in accounting.</p>
</div>
<div class="col-lg-4 category-content">
<span class="glyphicon glyphicon-education"></span><br />
<a href="job-list.php?search=education">Education</a>
<p>Explore education jobs.</p>
</div>
<div class="col-lg-4 category-content">
<span class="glyphicon glyphicon-usd"></span><br />
<a href="job-list.php?search=marketing">Marketing</a>
<p>Marketing roles available.</p>
</div>
</div>
<div class="row">
<div class="col-lg-4 col-lg-push-5">
<a href="job-list.php" class="thm-btn">Browse all categories</a>
</div>
</div>
</div>
</div>
</section>

<?php endif; ?>

<?php include_once 'footer.php'; ?>
<?php endif; ?>
</body>
</html>

