
<?php
	require_once("../classes.php");
	session_start();
	if (!isset($_SESSION['logged_in'])) {
		header('Location: /tcabs/login.php');
	} else {
		
		// if there was a post request
			if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
				// do something
				echo "search request";
			}
			
			if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
				// do something
				echo "update request for " . $_POST['update'];
			}
			
			if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
				// do something
				echo "delete request for " . $_POST['delete'];
			}
			$searchResults = [
    'The A Team'  => 'Database Implementation - HS1 2019',
	'The Coders'  => 'Intro to Programming - HS1 2019',
];
		}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Stylesheets -->
		<?php include "../styles/stylesheet.php"; ?>
		
		<title>Update/Delete Team - TCABS</title>
  </head>

  <body class="loggedin">
		<?php include "../views/header.php"; ?>
  
		<div class="content">
			<h2>Manage Teams > Update/Delete Team</h2><h2-date><?php echo date('d F, Y (l)'); ?></h2-date><br>
			<div>
		<?php 
		//Check the Users role to see if they have access to this
		$roleFound = FALSE;						
		foreach($_SESSION['loggedUser']->uRoles as $userType => $access) {
			if($userType=='admin') {
				$roleFound = TRUE;
				
				
		?>
			<!-- Show Search Form -->
				<form style="width: 90%; margin: auto;" action="updateTeam.php" method ="post" class="was-validated">
		<br>
  	  <p class="h4 mb-4 text-center">Update/Delete Team</p>

  <input type="text" id="searchQuery" name="searchQuery" class="form-control" placeholder="Enter Team Name" required>
  
    		<button class="btn btn-info my-4 btn-block" type="submit" name="submit" value="search">Search</button>
  </div>
		</form>
		
		
		<!-- Show Search Results -->
	<?php 
		if(isset($_POST['submit'])) {
		
	?>			
		<div>
<form action="updateUnits.php" method="post">
	 <table style="width: 100%;">

<tr>
    <th style="width: 40%;">Unit Code</th>
	<th style="width: 35%;">Unit Name</th>
	<th style="width: 15%;"></th>
    <th style="width: 15%;"></th>
    </tr>

<?php 
// print_r($searchResults);

foreach($searchResults as $key => $value) {
	$resultUnitCode = $key;
	$resultUnitName = $value;
	?>

<tr style="border-top: 1px solid lightgrey;">
<td><?php echo $resultUnitCode; // name?></td>
<td><?php echo $resultUnitName; // email?></td>
<td><button type="submit" class="btn btn-primary" name="update" value="<?php echo $resultUnitCode; //update button ?>" >Update</button></td>
<td><button type="submit" class="btn btn-danger" name="delete" value="<?php echo $resultUnitCode; //delete button ?>" >Delete</button></td>


 </tr>

<?php  }?>

</table>
</form>
		</div>
		<?php 
		}
		?>
				
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
  <?php include "../views/footer.php"; ?>  
</html>