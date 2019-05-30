
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
				
				$searchQuery = $_POST['searchQuery'];
				$query = mysqli_query($conn, "select unit.unitCode, Offeringstaff.offeringstaffid, users.email, users.fName, users.lName, unit.unitName, teachingperiod.term, teachingperiod.year
							from OfferingStaff 
							left join users on offeringstaff.UserName = users.email 
							left join unitoffering on OfferingStaff.UnitOfferingID = UnitOffering.UnitOfferingID 
							left join unit on unitoffering.unitCode = unit.unitCode 
							left join teachingperiod on unitoffering.term = teachingperiod.term and unitoffering.year = teachingperiod.year 
							where unit.unitCode like '%$searchQuery%' or unit.unitName like '%$searchQuery%' or users.fName like '%$searchQuery%' or users.lName like '%$searchQuery%' 
							order by users.fName
							");
							$searchResults = mysqli_fetch_all($query, MYSQLI_ASSOC);
			}
			
			if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
				// do something
				echo "update request for " . $_POST['update'];
			}
			
			if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
				// do something
				// echo "delete request for " . $_POST['delete'];
				
						/* Switch off auto commit to allow transactions*/
		mysqli_autocommit($conn, FALSE);
		$query_success = TRUE;
	
			$delID = $_POST['delete'];
			
			// Delete the Staff Offer
			$stmt = $GLOBALS['conn']->prepare("delete from offeringstaff where offeringstaffID = ?");
			$stmt->bind_param("s", $delID);
			
			try {
				$stmt->execute();
				
				mysqli_commit($conn);
				echo "<script type='text/javascript'>alert('Staff offering deleted successfully');</script>";
			} catch(mysqli_sql_exception $e) {
				echo "<script type='text/javascript'>alert('Cannot delete, there is a team associated with this staff offering');</script>";
				mysqli_rollback($conn);
			}
					
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
		<?php include "../styles/stylesheet.php"; ?>
		
		<title>Update/Delete Team - TCABS</title>
  </head>

  <body class="loggedin">
		<?php include "../views/header.php"; ?>
  
		<div class="content">
			<h2>Manage Staff Offering > Delete Staff Offering</h2><h2-date><?php echo date('d F, Y (l)'); ?></h2-date><br>
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
			<!-- Show Search Form -->
				<form style="width: 90%; margin: auto;"  method ="post" class="was-validated">
		<br>
  	  <p class="h4 mb-4 text-center">Delete Staff Offering</p>

  <input type="text" id="searchQuery" name="searchQuery" class="form-control" placeholder="Enter Team Name" required>
  
    		<button class="btn btn-info my-4 btn-block" type="submit" name="submit" value="search">Search</button>
  </div>
		</form>
		
		
		<!-- Show Search Results -->
	<?php 
		if(isset($_POST['submit'])) {
		
	?>			
		<div>
<form  method="post">
	 <table style="width: 100%;">

<tr>
	<th style="width: 20%;">Name</th>
    <th style="width: 10%;">Unit Code</th>
	<th style="width: 35%;">Unit Offering</th>
								<th style="width: 8%;">Semester</th>
							<th style="width: 8%;">Year</th>
    <th style="width: 15%;"></th>
    </tr>

<?php 
// print_r($searchResults);

foreach($searchResults as $key => $value) {
	$UnitCode = $value['unitCode'];
	$UnitOffer = $value['unitName'];
	
	$Name = $value['fName'] . " " . $value['lName'];
	?>

<tr style="border-top: 1px solid lightgrey;">
<td><?php echo $Name; // name?></td>
<td><?php echo $UnitCode; // name?></td>
<td><?php echo $UnitOffer; // email?></td>
<td><?php echo $value['term'];?></td>
<td><?php echo $value['year'];?></td>
<td><button type="submit" class="btn btn-danger" name="delete" value="<?php echo $value['offeringstaffid']; //delete button ?>" >Delete</button></td>


 </tr>

<?php  }?>

</table>
</form>
		</div>
		<?php 
		
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