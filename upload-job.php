
<?php
session_start();
include_once 'config.php';
include_once 'header.php';
if(isset($_SESSION['username'])!="admin"){
$_SESSION['msg']="1";
header("location:login.php");
exit();
}
$c_id = isset($_SESSION['username']) ? $_SESSION['username'] : null;

if ($c_id === null) {
    echo "You need to log in as a recruiter.";
    exit;
}
?>
<body>
<section class="body-section">
<div class="container">
<div class="row">
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-lg-push-3 col-sm-push-3 col-md-push-3 col-xs-push-3 inner-body">
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST'){ 
$sql = $con->prepare("INSERT INTO jobs (job_title, category, sub_category, employer, skills, qualification, experience, job_description, company_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$sql->bind_param("sssssssss", $title, $category, $subcategory, $employer, $skills, $qualification, $experience, $job_description, $c_id);
$title=$_POST['title'];
$category=$_POST['category'];
$subcategory=$_POST['subcategory'];
$employer=$_POST['employer'];
$skills=$_POST['skills'];
$qualification=$_POST['qualification'];
$experience=$_POST['experience'];
$job_description=$_POST['description'];
$sql->execute();
}
?>
<div class="container">
<h2>upload a job</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
<label>job title: <input type="text" name="title"   /> </label> <br />
<label>category:  <input type="text" name="category"   /></label><br />
<label>sub category:  <input type="text" name="subcategory"   /></label><br />
<label>employer:  <input type="text" name="employer"   /></label><br />
<label>skills:  <input type="text" name="skills"   /></label><br />
<label>qualification:  <input type="text" name="qualification"   /></label><br />
<label>experience: <input type="text" name="experience"   /> </label> <br />
<label>job description:  <textarea name="description">
</textarea></label><br />
<input type="submit" value="upload" />
</form>
</div>
</div>
</div>
</div>
</section>
<!--select  jobs.job_id,jobs.job_title,count(*) as applied from jobs,candidates where jobs.job_id=candidates.job_id group by jobs.job_id
-->
</body>
</html>
