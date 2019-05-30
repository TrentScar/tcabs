
<?php
	require_once("classes.php");
	session_start();
	if (!isset($_SESSION['logged_in'])) {
		header('Location: login.php');
	} else {
	
		if($_SERVER['REQUEST_METHOD'] == 'POST') {

		}
	
	}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Stylesheets -->
		<?php include "styles/stylesheet.php"; ?>
		
		<title>Manage Teams - TCABS</title>
  </head>

  <body class="loggedin">
		<?php include "views/header.php"; ?>
  
		<div class="content">
			<h2>Manage Teams</h2><h2-date><?php echo date('d F, Y (l)'); ?></h2-date><br>
			<div>
		<?php 
		//Check the Users role to see if they have access to this
		$roleFound = FALSE;						
		foreach($_SESSION['loggedUser']->uRoles as $userType => $access) {
			if($userType=='convenor') {
				$roleFound = TRUE;
		?>
			
		<a type="button"  href="forms/addTeam.php" class="btn btn-primary btn-lg btn-block">Add Team</a>
		<a type="button"  href="forms/updateTeam.php" class="btn btn-primary btn-lg btn-block">Update or Delete Team</a>
		<a type="button"  href="forms/updateTeamFormation.php" class="btn btn-primary btn-lg btn-block">Update or Delete Team Formation</a>
				
		<?php  } }
		
		//If they dont have correct permission
		if ($roleFound == FALSE) { ?>
		
			<h2>Permission Denied</h2>
			<div>
			<p>Sorry, you do not have access to this page. Please contact your administrator.</p>
			</div>
		<?php  }  ?>
			</div>
		</div>
  </body>
  <?php include "views/footer.php"; ?>  
</html>