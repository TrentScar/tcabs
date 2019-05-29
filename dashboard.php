<!-- The main page of the system will show relevant functionality according to user role -->
<?php
	require_once("classes.php");
	session_start();

	if(!isset($_SESSION['logged_in'])) {
		header("location: /tcabs/login.php");
	}
	
	//$enrolObj = new Enrolment;
	//print_r($enrolObj->getUnitEnrolments("ICT30002", "Semester 1", "2019"));

	//$teamObj = new TeamMember;
	//print_r($teamObj->searchMembers("%a%"));
	
	//$teamObj = new TeamMember;
	//print_r($teamObj->getMembers(1));

	// subquery returns more than one row(error)
	//$uOffObj = new UnitOffering("STA10003");
	//$uOffObj->addUnitOff("ICT30001", "dtargaryen@gmail.com", "Semester 2", "2019", "2019-05-09");

	/* not relevant now
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$_SESSION['logged_in'] = FALSE;	
		header("location: login.php");
		exit();
	}	else if($_SESSION['logged_in'] == TRUE) {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			//require("signup.php");
		}
	}
	 */
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Stylesheets -->
		<?php include "styles/stylesheet.php"; ?>
  </head>

  <body class="loggedin">
		<?php include "views/header.php"; ?>
  
		<div class="content">
			<h2>Welcome, <?php echo $_SESSION['loggedUser']->fName?></h2><h2-date><?php echo date('d F, Y (l)'); ?></h2-date><br>
			<div>
				<p> Here </p>
			</div>
		</div>
  </body>
  <?php include "views/footer.php"; ?>  
</html>
