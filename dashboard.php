<!-- The main page of the system will show relevant functionality according to user role -->
<?php
	require_once("classes.php");
	session_start();

	if(!isset($_SESSION['logged_in'])) {
		header("location: /tcabs/login.php");
	}

	// ##Team Project##
	$tProjObj = new TeamProject; // make an object of class TeamProject

	// add project to registered team
	// need - Project Name - TeamName - Convenor - unitCode - term - year
	echo 'Adding Team Project<br>';
	$tProjObj->addTeamProject("Big Test Project", "just a name", "dtargaryen@gmail.com", "ICT30001", "Semester 2", "2018");

	// get TeamProject object using TeamProjectID with all info
	echo 'Getting Team Project ID = 1<br>';
	print_r($tProjObj->getTeamProject(1));
	echo '<br><br>';

	// search allocated projects to registered teams
	echo "Getting Team Project with searchQuery = 'pro'<br>";
	$searchQuery = "pro"; // search bar value may contain TeamName/ProjectName
	print_r($tProjObj->searchTeamProject("%{$searchQuery}%")); // print everything
	echo '<br><br>';

	// ##ProjectRole##
	$projRoleObj = new ProjRole; // make an object of class ProjRole

	// get Project Role object with all info - using role name
	echo 'Getting Project Role = "Project Manager"<br>';
	print_r($projRoleObj->getProjRole("Project Manager"));
	echo '<br><br>';

	// get all project roles in multi dimensional array
	echo 'Getting all project roles"<br>';
	print_r($projRoleObj->getAllProjRoles()); // can be used for dropdown
	echo '<br><br>';

	// search allocated projects to registered teams
	echo "Getting all Project roles with searchQuery = 'pro'<br>";
	$searchQuery = "pro"; // search bar value may contain Role Name/Role Desc
	print_r($projRoleObj->searchProjRole("%{$searchQuery}%")); // print everything
	echo '<br><br>';

	/*
	// add Project Role
	// need - RoleName - salary - desc
	echo 'Adding Project Role<br>';
	print_r($projRoleObj->getProjRole("Project Manager"));
	echo '<br><br>';
 */

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
				<p>Welcome</p>
			</div>
		</div>
  </body>
  <?php include "views/footer.php"; ?>  
</html>
