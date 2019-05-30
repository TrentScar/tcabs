<?php

	require_once("classes.php");
	session_start();

	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$_SESSION['logged_in'] = FALSE;	
		header("location: login.php");
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
  </head>

<?php include "views/header.php"; ?>

  <body class="loggedin">
	
		<div class="content">
			<h2>Welcome, <?php echo "{$_SESSION['loggedUser']->fName}"; ?></h2>
			<div>
				<p>Your account details are below:</p>
				<table>
				<tr>
						<td>Name:</td>
						<td><?php echo "{$_SESSION['loggedUser']->fName} {$_SESSION['loggedUser']->lName}"; ?></td>
					</tr>
					<tr>
						<td>Registered Email:</td>
						<td><?php echo "{$_SESSION['loggedUser']->email}"?></td>
					</tr>
					<tr>
					<tr>
						<td>Phone Number:</td>
						<td><?php echo "{$_SESSION['loggedUser']->pNum}"?></td>
					</tr>
					<tr>
						<td>Gender</td>
						<td><?php echo "{$_SESSION['loggedUser']->gender}"?></td>
					</tr>
						<td>Access Levels:</td>
						<!--<td><?php# echo "{$_SESSION['loggedUser']->pNum}"?></td>
					</tr>
				</table>
			</div>
		</div>
  </body>
  
  	<?php include "views/footer.php";  ?>  
</html>
