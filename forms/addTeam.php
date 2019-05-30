
<?php
	require_once("../classes.php");
	session_start();
	if (!isset($_SESSION['logged_in'])) {
		header('Location: /tcabs/login.php');
	} else {
	
		// if there was a post request
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
					/* Switch off auto commit to allow transactions*/
		mysqli_autocommit($conn, FALSE);
		$query_success = TRUE;
	
				$unitOfferingID = $_POST['unitOfferingID'];
			// print_r($_POST);

			// echo $unitOfferingID;
			$query = mysqli_query($conn, "SELECT unitoffering.unitOfferingID, unit.unitName, unitoffering.unitCode, teachingperiod.term, teachingperiod.year, users.fName, users.lName, users.email
			from unitoffering left join unit on unitoffering.unitCode = unit.unitCode 
			left join users on unitoffering.cUserName = users.email 
			left join teachingperiod on unitoffering.term = teachingperiod.term and unitoffering.year = teachingperiod.year 
			where unitoffering.unitOfferingID = '$unitOfferingID'");
			$getunitOfferingID = mysqli_fetch_row($query);
			// print_r($getunitOfferingID);
			
			// echo $_POST['teamName'], $getunitOfferingID[7], $getunitOfferingID[2], $getunitOfferingID[3], $getunitOfferingID[4];
			// Add the team
			$stmt = $GLOBALS['conn']->prepare("CALL TCABSTeamAddTeam(?, ?, ?, ?, ?)");
			$stmt->bind_param("sssss",$_POST['teamName'], $getunitOfferingID[7], $getunitOfferingID[2], $getunitOfferingID[3], $getunitOfferingID[4]);
			
			try {
				$stmt->execute();
				
				mysqli_commit($conn);
				echo "<script type='text/javascript'>alert('Created Team Successfully');</script>";
			} catch(mysqli_sql_exception $e) {
				echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
				mysqli_rollback($conn);
			}
			}
		}
?>

	  <?php 
			$query = mysqli_query($conn, "SELECT unitoffering.unitOfferingID, unit.unitName, unitoffering.unitCode, teachingperiod.term, teachingperiod.year, users.fName, users.lName 
							from unitoffering left join unit on unitoffering.unitCode = unit.unitCode left join users on unitoffering.cUserName = users.email 
							left join teachingperiod on unitoffering.term = teachingperiod.term and unitoffering.year = teachingperiod.year 
							order by teachingperiod.year desc ");
			$unitOfferingAvailable = mysqli_fetch_all($query, MYSQLI_ASSOC);
			
			$query = mysqli_query($conn, "select unit.unitCode, Offeringstaff.offeringstaffid, offeringstaff.unitofferingid, users.email, users.fName, users.lName, unit.unitName, teachingperiod.term, teachingperiod.year from OfferingStaff left join users on offeringstaff.UserName = users.email left join unitoffering on OfferingStaff.UnitOfferingID = UnitOffering.UnitOfferingID left join unit on unitoffering.unitCode = unit.unitCode left join teachingperiod on unitoffering.term = teachingperiod.term and unitoffering.year = teachingperiod.year order by users.fName");
			$StaffOfferingAvailable = mysqli_fetch_all($query, MYSQLI_ASSOC);
			
			
			?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Stylesheets -->
		<?php include "../styles/stylesheet.php"; ?>
		
		<title>Add Team - TCABS</title>
  </head>

  <body class="loggedin">
		<?php include "../views/header.php"; ?>
  
		<div class="content">
			<h2>Manage Teams > Add Team</h2><h2-date><?php echo date('d F, Y (l)'); ?></h2-date><br>
			<div>
		<?php 
		//Check the Users role to see if they have access to this
		$roleFound = FALSE;						
		foreach($_SESSION['loggedUser']->uRoles as $userType => $access) {
			if($userType=='convenor') {
				$roleFound = TRUE;
		?>
			
				<form style="width: 90%; margin: auto;" action="addTeam.php" method ="post" class="was-validated">
		<br>
  	  <p class="h4 mb-4 text-center">Add Team</p>
	<input type="text" id="teamName" name="teamName" class="form-control" placeholder="Team Name" required /><br>
		
		
<select class="browser-default custom-select" id="unitOfferingID" name="unitOfferingID" required>
 	  		<option value="" disabled="" selected="">Select Unit Offering</option>
 	    	
			<!-- Populate based on units table here -->
			
			<?php 
			foreach($StaffOfferingAvailable as $key => $value) {
			$unitName = $value['year'] . " - " . $value['term'] . " - " . $value['unitCode'] . " - " . $value['unitName'];
			?>
			<option value="<?php echo $value['unitofferingid']; ?>"> <?php echo $unitName .  $value['unitofferingid']; ?> </option>
			<?php } ?>
			
			
			</select>		
			<br>
		
<br><br>
  		<button class="btn btn-info my-4 btn-block" type="submit">Add Team</button>
		</form>
				
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