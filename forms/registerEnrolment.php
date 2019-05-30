<?php
	require_once("../classes.php");
	session_start();
	if (!isset($_SESSION['logged_in'])) {
		header('Location: /tcabs/login.php');
		exit();
	} else {

		// check if user has permission to access the page
		if(!$_SESSION['loggedUser']->uRoles['admin']) {
			header('Location: /tcabs/dashboard.php');
		} else {
		
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				if(isset($_POST['submit'])) {
					$unitObj = new Unit;
					if($_POST['submit'] === "addUnit") {
			
			$unitOfferingID = $_POST['unitCode'];
			$query = mysqli_query($conn, "SELECT * from unitoffering where unitOfferingID = '$unitOfferingID'");
			$getUnitOfferingDetails = mysqli_fetch_row($query);
			
			echo $_POST['convenor'], $getUnitOfferingDetails[1], $getUnitOfferingDetails[3], $getUnitOfferingDetails[4];
		/* Switch off auto commit to allow transactions*/
		mysqli_autocommit($conn, FALSE);
		$query_success = TRUE;


			
			// Add theEnrolment
			$stmt = $GLOBALS['conn']->prepare("CALL TCABSENROLMENTAddNewEnrolment(?, ?, ?, ?)");
			$stmt->bind_param("ssss", $_POST['convenor'], $getUnitOfferingDetails[1], $getUnitOfferingDetails[3], $getUnitOfferingDetails[4]);
			

			try {
				$stmt->execute();
				mysqli_commit($conn);
				echo "<script type='text/javascript'>alert('Enrolment added successfully');</script>";
			} catch(mysqli_sql_exception $e) {
				echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
				mysqli_rollback($conn);
			}

			$stmt->close();
		}
						
						
						
				if($_POST['submit'] === "bulkAddUnits") { 
			
				}
				
				if($_POST['submit'] === "search") {
						if($_POST['searchQuery'] == null) {
							echo "<script type='text/javascript'>alert('Search Box empty');</script>";
						} else {
							try {
							$searchQuery = $_POST['searchQuery'];
							$query = mysqli_query($conn, "SELECT enrolment.enrolmentID, unitoffering.unitOfferingID, unit.unitcode, unit.unitName, teachingperiod.term, teachingperiod.year, users.fName, users.lName 
							from enrolment 
                            left join unitoffering on unitoffering.unitOfferingID = enrolment.unitOfferingID
                            left join unit on unitoffering.unitCode = unit.unitCode 
                            left join users on enrolment.sUserName = users.email 
							left join teachingperiod on unitoffering.term = teachingperiod.term and unitoffering.year = teachingperiod.year 
							where unit.unitCode like '%$searchQuery%' or unit.unitName like '%$searchQuery%' or users.fName like '%$searchQuery%' or users.lName like '%$searchQuery%' 
							order by teachingperiod.year desc ");
							$searchResults = mysqli_fetch_all($query, MYSQLI_ASSOC);
							// print_r($searchResults);
								if($searchResults == null) {
									echo "<script type='text/javascript'>alert('Oops nothing found!');</script>";
								}
							} catch(mysqli_sql_exception $e) {
								echo $e->getMessage();
								exit();
								echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
							}
						}
					} 
					
					if($_POST['submit'] == 'editUnit') {
						
					} 
					}
					
					if(isset($_POST['update'])) {
					}

				 if(isset($_POST['delete'])) {
					echo "delete";
		/* Switch off auto commit to allow transactions*/
		mysqli_autocommit($conn, FALSE);
		$query_success = TRUE;
	
			$delEnrolledUser = $_POST['delete'];
			$query = mysqli_query($conn, "SELECT enrolment.enrolmentID, unitoffering.unitOfferingID, unit.unitcode, unit.unitName, teachingperiod.term, teachingperiod.year, users.fName, users.lName, users.email
			from enrolment 
			left join unitoffering on unitoffering.unitOfferingID = enrolment.unitOfferingID 
			left join unit on unitoffering.unitCode = unit.unitCode 
			left join users on enrolment.sUserName = users.email 
			left join teachingperiod on unitoffering.term = teachingperiod.term and unitoffering.year = teachingperiod.year 
			where enrolment.enrolmentID = '$delEnrolledUser'");
			$getEnrolmentDetails = mysqli_fetch_row($query);
			print_r($getEnrolmentDetails);
			
			// Delete the Enrolment
			$stmt = $GLOBALS['conn']->prepare("CALL TCABSENROLMENTDeleteEnrolment(?, ?, ?, ?)");
			$stmt->bind_param("ssss", $getEnrolmentDetails[8], $getEnrolmentDetails[2], $getEnrolmentDetails[4], $getEnrolmentDetails[5]);
			
			try {
				$stmt->execute();
				
				mysqli_commit($conn);
				echo "<script type='text/javascript'>alert('Enrolment deleted successfully');</script>";
			} catch(mysqli_sql_exception $e) {
				echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
				mysqli_rollback($conn);
			}
					
					}
				
			}
			if(isset($_GET['status']) && $_GET['status'] == "succ") {
				echo "<script type='text/javascript'>alert('CSV Import Success!');</script>";
			}
			
			if(isset($_GET['status']) && $_GET['status'] == "invalid_file") {
				echo "<script type='text/javascript'>alert('CSV Import Failed. Invalid File');</script>";
			}
			
			
			if(isset($_GET['status']) && $_GET['status'] == "err") {
				echo "<script type='text/javascript'>alert('CSV Import Error. Please check');</script>";
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
			
			$query = mysqli_query($conn, "select fname, lname, users.email from users left join usercat on users.email = usercat.email where usercat.userType = 'student' and fname is not null");
			$convenorResult = mysqli_fetch_all($query, MYSQLI_ASSOC);
			
			
			?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Stylesheets -->
		<?php include "../styles/stylesheet.php"; ?>
		<title>Manage Enrolment - TCABS</title>
  </head>

  <body class="loggedin">
		<?php include "../views/header.php"; ?>
		<div class="content">
			<h2>Manage Enrolment</h2><h2-date><?php echo date('d F, Y (l)'); ?></h2-date><br>
		<div>

		<?php 
			//show tabs if not in update mode
			if(!isset($_POST['update'])) {
		?>

		<ul class="nav nav-tabs">
  		<li class="nav-item">
    		<a class="nav-link <?php if((isset($_POST['submit']) && $_POST['submit'] == 'addUnit') || $_SERVER['REQUEST_METHOD'] == 'GET') { echo 'active';} ?>" data-toggle="tab" href="#home">Add</a>
  		</li>
		<li class="nav-item">
				<a class="nav-link <?php if(isset($_POST['submit']) && $_POST['submit'] == 'bulkImport') { echo 'active';} ?>" data-toggle="tab" href="#menu1">Bulk Import via CSV</a>
		</li>
    	<li class="nav-item">
    		<a class="nav-link <?php if((isset($_POST['submit']) && $_POST['submit'] == 'search') || $_POST['submit']== 'editUnit') { echo 'active';} ?>" data-toggle="tab" href="#menu2">Update/Delete</a>
  		</li>
		</ul>

		<?php } ?>

		<!-- Tab panes -->
		<div class="tab-content">

			<!-- Edit only when Update button pressed -->
			<?php
				if(isset($_POST['update'])) { ?>

			<?php  } ?>


			<!-- Tab 1 ------------------------------->
  		<div class="tab-pane container <?php if((isset($_POST['submit']) && $_POST['submit'] == 'addUnit') || $_SERVER['REQUEST_METHOD'] == 'GET') { echo 'active show';} ?>" id="home">
<form style="width: 90%; margin: auto;" action="registerEnrolment.php" method ="post" class="was-validated">
		<br>
  	  <p class="h4 mb-4 text-center">Add Enrolment</p>
	  

			<select class="browser-default custom-select" id="unitCode" name="unitCode" required>
 	  		<option value="" disabled="" selected="">Select Unit Offering</option>
 	    	
			<!-- Populate based on units table here -->
			
			<?php 
			foreach($unitOfferingAvailable as $key => $value) {
			$unitName = $value['year'] . " - " . $value['term'] . " - " . $value['unitCode'] . " - " . $value['unitName'];
			?>
			<option value="<?php echo $value['unitOfferingID']; ?>"><?php echo $unitName; ?></option>
			<?php } ?>
			
 	  	</select>	
		
		<br><br>

	
			<select class="browser-default custom-select" id="convenor" name="convenor" required>
 	  		<option value="" disabled="" selected="">Select Student</option>
 	    	
			<!-- Populate based on usertype convenor here -->
			
						<?php 
			foreach($convenorResult as $key => $value) {
			?>
			<option value="<?php echo $value['email']; ?>"><?php echo $value['fname'] . " " . $value['lname'] ; ?></option>
			<?php } ?>
			
 	  	</select>	
		
		
				<br>
  		<button class="btn btn-info my-4 btn-block" type="submit" name="submit" value="addUnit">Add Unit Offering</button>
		</form>
			</div>
			
		<!-- Tab 2 -->	
  	<div class="tab-pane container fade <?php if(isset($_POST['submit']) && $_POST['submit'] == 'bulkImport') { echo 'active show';} ?>" id="menu1">
  		<br>
  	  	<p class="h4 mb-4 text-center">Bulk Import via CSV</p>
		<div class="col-mb-4 text-center"> 
		<a class="btn btn-outline-info" href="../csv/enrolment.csv" role="button">CSV Template Download</a>
		</div>
		<br><br>
  			<div class="form-group">
      <div class="custom-file">
	  <form action="importCSVEnrolment.php" method="post" enctype="multipart/form-data">
    <input type="file" class="custom-file-input" id="file" name="file" required>
    <label class="custom-file-label" for="csvFile">Choose file</label>

  </div>

<script>
// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>
  			</div>
  			<button class="btn btn-info my-4 btn-block" type="submit" name="importSubmit" value="IMPORT">Import</button>
			</form>
		</div>		

			<!-- Tab 3 -->
  		<div class="tab-pane container fade <?php if((isset($_POST['submit']) && $_POST['submit'] == 'search') || $_POST['submit']== 'editUnit'  || isset($_POST['delete'])) { echo 'active show';} ?>" id="menu2" >
				<form method="POST" class="was-validated" ><br/>
  	 		 	<p class="h4 mb-4 text-center">Update/Delete Enrolment</p>
					<div class="search-box">
						<input type="text" id="searchQuery" name="searchQuery" class="form-control" placeholder="Enter Unit Code, Name or Student Name" required>
						<button class="btn btn-info my-4 btn-block" type="submit" name="submit" value="search">Search</button>
						<div class="result"></div>
					</div>
				</form><br>

				<!-- Show Search Results -->
			<?php 
				if(isset($_POST['submit']) && $_POST['submit'] == 'search') {
			?>		

			<div>
				<form action="registerEnrolment.php" method="post">
					<table style="width: 100%;">
						<tr>
    					<th style="width: 10%;">Unit Code</th>
							<th style="width: 25%;">Unit Name</th>
							<th style="width: 18%;">Student</th>
							<th style="width: 12%;">Semester</th>
							<th style="width: 8%;">Year</th>
    					<th style="width: 15%;"></th>
    				</tr>

						<?php 

							foreach($searchResults as $key => $value) {
								$name = $value['unitcode'];
								$email = $value['unitName'];
								$convenor = $value['fName'] . " " . $value['lName'];
						?>

						<tr style="border-top: 1px solid lightgrey;">
							<td><?php echo $name;?> </td>
							<td><?php echo $email;?></td>
							<td><?php echo $convenor;?></td>
							<td><?php echo $value['term'];?></td>
							<td><?php echo $value['year'];?></td>
							<td><button type="submit" class="btn btn-danger" name="delete" value="<?php echo $value['enrolmentID'];?>" >Delete Enrolment</button></td>
						</tr>

						<?php  }?>

					</table>
				</form><br>
			</div>
			<?php  }?>
			</div></div></div>
		</div>
	</body>
  <?php include "../views/footer.php";  ?>  

	<script>
		// Add the following code if you want the name of the file appear on select
		$(".custom-file-input").on("change", function() {
			var fileName = $(this).val().split("\\").pop();
			$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
		});
	</script>

</html>
