
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
			$query = mysqli_query($conn, "SELECT unitoffering.unitOfferingID, unit.unitName, unitoffering.unitCode, teachingperiod.term, teachingperiod.year, users.fName, users.lName, users.email
			from unitoffering left join unit on unitoffering.unitCode = unit.unitCode 
			left join users on unitoffering.cUserName = users.email 
			left join teachingperiod on unitoffering.term = teachingperiod.term and unitoffering.year = teachingperiod.year 
			where unitoffering.unitOfferingID = '$unitOfferingID'");
			$getunitOfferingID = mysqli_fetch_row($query);
			//print_r($getunitOfferingID);
			
			// dd to Staff Offering
			$stmt = $GLOBALS['conn']->prepare("CALL TCABSOFFERINGSTAFFAddOfferingStaff(?, ?, ?, ?)");
			$stmt->bind_param("ssss",$_POST['userEmail'], $getunitOfferingID[2], $getunitOfferingID[3], $getunitOfferingID[4]);
			//echo $_POST['userEmail'], $getunitOfferingID[2], $getunitOfferingID[3], $getunitOfferingID[4];
			try {
				$stmt->execute();
				
				mysqli_commit($conn);
				echo "<script type='text/javascript'>alert('Created Staff Offering Successfully');</script>";
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
			
			$query = mysqli_query($conn, "select fname, lname, users.email from users left join usercat on users.email = usercat.email where usercat.userType = 'admin' or usercat.userType = 'convenor' or usercat.userType = 'supervisor'   and fname is not null
group by email order by fname");
			$staffOfferingAvailable = mysqli_fetch_all($query, MYSQLI_ASSOC);
			// print_r($staffOfferingAvailable);
			
			?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Stylesheets -->
		<?php include "../styles/stylesheet.php"; ?>
		
		<title>Add Staff Offering - TCABS</title>
  </head>

  <body class="loggedin">
		<?php include "../views/header.php"; ?>
  
		<div class="content">
	
		
			<h2>Manage Users > Staff Offering</h2><h2-date><?php echo date('d F, Y (l)'); ?></h2-date><br>
			<div>
				<?php
				//Check the Users role to see if they have access to this
				$roleFound = FALSE;
				foreach($_SESSION['loggedUser']->uRoles as $userType => $access) {
					if($userType=='admin') {
						$roleFound = TRUE;
					} else if($userType=='convenor') {
						$roleFound = TRUE;
				} }?>
				
				<?php 
				//If they have the correct role to view the page
				if($roleFound == TRUE) { ?>
			
				<form style="width: 90%; margin: auto;" action="addOfferingStaff.php" method ="post" class="was-validated">
		<br>
  	  <p class="h4 mb-4 text-center">Add Staff Offering</p>
			<select class="browser-default custom-select" id="userEmail" name="userEmail" required>
 	  		<option value="" disabled="" selected="">Select User</option>
 	    	
			<!-- Populate based on user table here -->
			
			<?php 
			foreach($staffOfferingAvailable as $key => $value) {
			$Name = $value['fname'] . " " . $value['lname'];
			?>
			<option value="<?php echo $value['email']; ?>"><?php echo $Name; ?></option>
			<?php } ?>
			
			
			</select>	
		<br> <br>
		
			<select class="browser-default custom-select" id="unitOfferingID" name="unitOfferingID" required>
 	  		<option value="" disabled="" selected="">Select Unit Offering</option>
 	    	
			<!-- Populate based on units table here -->
			
			<?php 
			foreach($unitOfferingAvailable as $key => $value) {
			$unitName = $value['year'] . " - " . $value['term'] . " - " . $value['unitCode'] . " - " . $value['unitName'];
			?>
			<option value="<?php echo $value['unitOfferingID']; ?>"><?php echo $unitName; ?></option>
			<?php } ?>
			
			
			</select>	
			<br>
		
<br>
  		<button class="btn btn-info my-4 btn-block" type="submit">Add Staff Offering</button>
		</form>
				
		<?php  } 
		
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