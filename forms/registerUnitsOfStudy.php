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
			
			$teachingSemester = $_POST['teachingSemester'];
			$teachingYear = $_POST['teachingYear'];
			// $query = mysqli_query($conn, "SELECT `EndDate` from teachingperiod where term = '$teachingSemester' and year = '$teachingYear' ");
			// $censusResult = mysqli_fetch_row($query);
			
		/* Switch off auto commit to allow transactions*/
		mysqli_autocommit($conn, FALSE);
		$query_success = TRUE;


			
			// Add the Unit Offering
			$stmt = $GLOBALS['conn']->prepare("CALL TCABSUNITOFFERINGAddNewOffering(?, ?, ?)");
			$stmt->bind_param("sss", $_POST['unitCode'], $_POST['teachingSemester'], $_POST['teachingYear']);
			
			// Add the Convenor to OfferingStaff table
			$stmt2 = $GLOBALS['conn']->prepare("CALL TCABSOFFERINGSTAFFAddOfferingStaff(?, ?, ?, ?)");
			$stmt2->bind_param("ssss", $_POST['convenor'], $_POST['unitCode'], $_POST['teachingSemester'], $_POST['teachingYear']);
			
			// Add the Convenor
			$stmt3 = $GLOBALS['conn']->prepare("CALL TCABSUNITOFFERINGSetConvenor(?, ?, ?, ?)");
			$stmt3->bind_param("ssss", $_POST['convenor'], $_POST['unitCode'], $_POST['teachingSemester'], $_POST['teachingYear']);
			
			// Add the CencusDate
			$stmt4 = $GLOBALS['conn']->prepare("CALL TCABSUNITOFFERINGSetCensusDate(?, ?, ?, ?)");
			$stmt4->bind_param("ssss", $_POST['unitCode'], $_POST['teachingSemester'], $_POST['teachingYear'], $_POST['censusDate']);

			try {
				$stmt->execute();
				$stmt2->execute();
				$stmt3->execute();
				$stmt4->execute();
				mysqli_commit($conn);
				echo "<script type='text/javascript'>alert('Unit Offering added successfully');</script>";
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
							$query = mysqli_query($conn, "SELECT unitoffering.unitOfferingID, unit.unitName, unitoffering.unitCode, teachingperiod.term, teachingperiod.year, users.fName, users.lName from unitoffering left join unit on unitoffering.unitCode = unit.unitCode left join users on unitoffering.cUserName = users.email left join teachingperiod on unitoffering.term = teachingperiod.term and unitoffering.year = teachingperiod.year where unitoffering.unitCode like '%$searchQuery%' or unit.unitName like '%$searchQuery%' order by teachingperiod.year desc ");
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
						
		/* Switch off auto commit to allow transactions*/
		mysqli_autocommit($conn, FALSE);
		$query_success = TRUE;
	
			// echo $_POST['convenor'], $_POST['unitCode'], $_POST['teachingSemester'], $_POST['teachingYear'];
			
			// Get the unit info 
			
			
			// Edit the Unit Offering
			$stmt = $GLOBALS['conn']->prepare("CALL TCABSUNITOFFERINGEditOffering(?, ?, ?)");
			$stmt->bind_param("sss", $_POST['unitCode'], $_POST['teachingSemester'], $_POST['teachingYear']);
			
			// Add the Convenor to OfferingStaff table
			$stmt2 = $GLOBALS['conn']->prepare("CALL TCABSOFFERINGSTAFFEditOfferingStaff(?, ?, ?, ?)");
			$stmt2->bind_param("ssss", $_POST['convenor'], $_POST['unitCode'], $_POST['teachingSemester'], $_POST['teachingYear']);
			
			// Add the Convenor
			$stmt3 = $GLOBALS['conn']->prepare("CALL TCABSUNITOFFERINGSetConvenor(?, ?, ?, ?)");
			$stmt3->bind_param("ssss", $_POST['convenor'], $_POST['unitCode'], $_POST['teachingSemester'], $_POST['teachingYear']);
			
			// Add the CencusDate
			$stmt4 = $GLOBALS['conn']->prepare("CALL TCABSUNITOFFERINGSetCensusDate(?, ?, ?, ?)");
			$stmt4->bind_param("ssss", $_POST['unitCode'], $_POST['teachingSemester'], $_POST['teachingYear'], $_POST['censusDate']);

			try {
				$stmt->execute();
				$stmt2->execute();
				$stmt3->execute();
				$stmt4->execute();
				mysqli_commit($conn);
				echo "<script type='text/javascript'>alert('Unit Offering edited successfully');</script>";
			} catch(mysqli_sql_exception $e) {
				echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
				mysqli_rollback($conn);
			}

			$stmt->close();


					} 
					}
					
					if(isset($_POST['update'])) {
					}

				 if(isset($_POST['delete'])) {
					echo "delete";
		/* Switch off auto commit to allow transactions*/
		mysqli_autocommit($conn, FALSE);
		$query_success = TRUE;
	
			
			
			// Delete the Unit Offering
			$stmt = $GLOBALS['conn']->prepare("CALL TCABSDeleteUnitOffering(?)");
			$stmt->bind_param("s", $_POST['delete']);
			
			try {
				$stmt->execute();
				
				mysqli_commit($conn);
				echo "<script type='text/javascript'>alert('Unit offering deleted successfully');</script>";
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
			$nUnitOffering = new Unit;
			$unitsAvailable = $nUnitOffering->searchUnit("%");
			// print_r($unitsAvailable);
			
			
			$query = mysqli_query($conn, "select distinct term from teachingperiod");
			$teachingSemResult = mysqli_fetch_all($query, MYSQLI_ASSOC);
			
			$query = mysqli_query($conn, "select distinct year from teachingperiod");
			$teachingYearResult = mysqli_fetch_all($query, MYSQLI_ASSOC);
			
			$query = mysqli_query($conn, "select fname, lname, users.email from users left join usercat on users.email = usercat.email where usercat.userType = 'convenor'");
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
		<title>Manage Units of Study - TCABS</title>
  </head>

  <body class="loggedin">
		<?php include "../views/header.php"; ?>
		<div class="content">
			<h2>Manage Units of Study</h2><h2-date><?php echo date('d F, Y (l)'); ?></h2-date><br>
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
				if(isset($_POST['update'])) {
					
					$searchQuery = $_POST['update'];
					$query = mysqli_query($conn, "SELECT unit.unitName, unitoffering.unitCode, teachingperiod.term, teachingperiod.year, users.fName, users.lName, users.email, unitoffering.censusdate from unitoffering left join unit on unitoffering.unitCode = unit.unitCode left join users on unitoffering.cUserName = users.email left join teachingperiod on unitoffering.term = teachingperiod.term and unitoffering.year = teachingperiod.year where unitoffering.unitOfferingID = '$searchQuery' order by teachingperiod.year desc ");
					$searchResults = mysqli_fetch_all($query, MYSQLI_ASSOC);
					// print_r($searchResults);		
					foreach($searchResults as $key => $value) {
						$name = $value['unitCode'];
						$email = $value['unitName'];
						$eTerm = $value['term'];
						$eYear = $value['year'];
						$eConvenorEmail = $value['email'];
						$eConvenorName = $value['fName'] . " " . $value['lName'];
						$eCensusDate = $value['censusdate'];
			?>
			
			<div>
				<form action="registerUnitsOfStudy.php" method="post" class="was-validated" style="width: 90%; margin: auto;"><br>
					<p class="h4 mb-4 text-center">Edit Unit Offering (<?php echo ($_POST['update']); ?>)</p>
<select class="browser-default custom-select" id="unitCode" name="unitCode" disabled>
 	  		<option value="" disabled="" selected="">Select Unit Code</option>
 	    	
			<!-- Populate based on units table here -->
			<option selected hidden value="<?php echo $value['unitCode']; ?>"><?php echo $value['unitCode'] . " -  " . $value['unitName']; ?></option>
			<?php 
			foreach($unitsAvailable as $key => $value) {
			$unitName = $value['unitCode'] . " - " . $value['unitName'];
			?>
			<option value="<?php echo $value['unitCode']; ?>"><?php echo $unitName; ?></option>
			<?php } ?>
			
 	  	</select>
		<br>
		<br>
		
			<select class="browser-default custom-select" id="teachingSemester" name="teachingSemester" disabled>
 	  		<option value="" disabled="" selected="">Select Teaching Semester</option>
 	    	<option selected hidden value="<?php echo $eTerm; ?>"><?php echo $eTerm; ?></option>
			<!-- Populate based on Teaching Period table here -->
			
			<?php 
			foreach($teachingSemResult as $key => $value) {
			?>
			<option value="<?php echo $value['term']; ?>"><?php echo $value['term']; ?></option>
			<?php } ?>
			
 	  	</select>
		
		<br><br>
			<select class="browser-default custom-select" id="teachingYear" name="teachingYear" disabled>
 	  		<option value="" disabled="" selected="">Select Teaching Year</option>
 	    	<option selected hidden value="<?php echo $eYear; ?>"><?php echo $eYear; ?></option>
			<!-- Populate based on Teaching Period table here -->
			
						<?php 
			foreach($teachingYearResult as $key => $value) {
			?>
			<option value="<?php echo $value['year']; ?>"><?php echo $value['year']; ?></option>
			<?php } ?>
			
 	  	</select>		
		
		<br><br>

	
			<select class="browser-default custom-select" id="convenor" name="convenor" required>
 	  		<option value="" disabled="" selected="">Select Convenor</option>
 	    	<option selected hidden value="<?php echo $eConvenorEmail; ?>"><?php echo $eConvenorName; ?></option>
			<!-- Populate based on usertype convenor here -->
			
						<?php 
			foreach($convenorResult as $key => $value) {
			?>
			<option value="<?php echo $value['email']; ?>"><?php echo $value['fname'] . " " . $value['lname'] ; ?></option>
			<?php } ?>
			
 	  	</select>	
		
		
				<br><br><br>
		<h4>Unit Census Date</h4>
    <input class="form-control" type="date" name="censusDate" id="censusDate" value="<?php echo $eCensusDate; ?>" required><br><br>

					<button class="btn btn-info my-4 btn-block" type="submit" name="submit" value="editUnit">Edit Unit</button>
				</form>
			</div>
			<?php  } ?>
			<?php  } ?>


			<!-- Tab 1 -->
  		<div class="tab-pane container <?php if((isset($_POST['submit']) && $_POST['submit'] == 'addUnit') || $_SERVER['REQUEST_METHOD'] == 'GET') { echo 'active show';} ?>" id="home">
<form style="width: 90%; margin: auto;" action="registerUnitsOfStudy.php" method ="post" class="was-validated">
		<br>
  	  <p class="h4 mb-4 text-center">Add Unit Offerings</p>
	  

			<select class="browser-default custom-select" id="unitCode" name="unitCode" required>
 	  		<option value="" disabled="" selected="">Select Unit Code</option>
 	    	
			<!-- Populate based on units table here -->
			
			<?php 
			foreach($unitsAvailable as $key => $value) {
			$unitName = $value['unitCode'] . " - " . $value['unitName'];
			?>
			<option value="<?php echo $value['unitCode']; ?>"><?php echo $unitName; ?></option>
			<?php } ?>
			
 	  	</select>
		<br>
		<br>
		
			<select class="browser-default custom-select" id="teachingSemester" name="teachingSemester" required>
 	  		<option value="" disabled="" selected="">Select Teaching Semester</option>
 	    	
			<!-- Populate based on Teaching Period table here -->
			
			<?php 
			foreach($teachingSemResult as $key => $value) {
			?>
			<option value="<?php echo $value['term']; ?>"><?php echo $value['term']; ?></option>
			<?php } ?>
			
 	  	</select>
		
		<br><br>
			<select class="browser-default custom-select" id="teachingYear" name="teachingYear" required>
 	  		<option value="" disabled="" selected="">Select Teaching Year</option>
 	    	
			<!-- Populate based on Teaching Period table here -->
			
						<?php 
			foreach($teachingYearResult as $key => $value) {
			?>
			<option value="<?php echo $value['year']; ?>"><?php echo $value['year']; ?></option>
			<?php } ?>
			
 	  	</select>		
		
		<br><br>

	
			<select class="browser-default custom-select" id="convenor" name="convenor" required>
 	  		<option value="" disabled="" selected="">Select Convenor</option>
 	    	
			<!-- Populate based on usertype convenor here -->
			
						<?php 
			foreach($convenorResult as $key => $value) {
			?>
			<option value="<?php echo $value['email']; ?>"><?php echo $value['fname'] . " " . $value['lname'] ; ?></option>
			<?php } ?>
			
 	  	</select>	
		
		
				<br><br><br>
		<h4>Unit Census Date</h4>
    <input class="form-control" type="date" name="censusDate" id="censusDate" value="" required>
		
<br><br>
  		<button class="btn btn-info my-4 btn-block" type="submit" name="submit" value="addUnit">Add Unit Offering</button>
		</form>
			</div>
			
		<!-- Tab 2 -->	
  	<div class="tab-pane container fade <?php if(isset($_POST['submit']) && $_POST['submit'] == 'bulkImport') { echo 'active show';} ?>" id="menu1">
		<br>
  	  	<p class="h4 mb-4 text-center">Bulk Import via CSV</p>
		<div class="col-mb-4 text-center"> 
		<a class="btn btn-outline-info" href="../csv/unitoffering.csv" role="button">CSV Template Download</a>
		</div>
		<br><br>
  			<div class="form-group">
      <div class="custom-file">
	  <form action="importCSVUnitOffering.php" method="post" enctype="multipart/form-data">
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
  		<div class="tab-pane container fade <?php if((isset($_POST['submit']) && $_POST['submit'] == 'search') || $_POST['submit']== 'editUnit') { echo 'active show';} ?>" id="menu2" >
				<form method="POST" class="was-validated" ><br/>
  	 		 	<p class="h4 mb-4 text-center">Update/Delete Unit Offering</p>
					<div class="search-box">
						<input type="text" id="searchQuery" name="searchQuery" class="form-control" placeholder="Enter Unit Name or Code" required>
						<button class="btn btn-info my-4 btn-block" type="submit" name="submit" value="search">Search</button>
						<div class="result"></div>
					</div>
				</form><br>

				<!-- Show Search Results -->
			<?php 
				if(isset($_POST['submit']) && $_POST['submit'] == 'search') {
			?>		

			<div>
				<form action="registerUnitsOfStudy.php" method="post">
					<table style="width: 100%;">
						<tr>
    					<th style="width: 10%;">Unit Code</th>
							<th style="width: 20%;">Unit Name</th>
							<th style="width: 20%;">Convenor</th>
							<th style="width: 12%;">Semester</th>
							<th style="width: 12%;">Year</th>
							<th style="width: 15%;"></th>
    					<th style="width: 15%;"></th>
    				</tr>

						<?php 

							foreach($searchResults as $key => $value) {
								$name = $value['unitCode'];
								$email = $value['unitName'];
								$convenor = $value['fName'] . " " . $value['lName'];
						?>

						<tr style="border-top: 1px solid lightgrey;">
							<td><?php echo $name;?></td>
							<td><?php echo $email;?></td>
							<td><?php echo $convenor;?></td>
							<td><?php echo $value['term'];?></td>
							<td><?php echo $value['year'];?></td>
							<td><button type="submit" class="btn btn-primary" name="update" value="<?php echo $value['unitOfferingID'];?>" >Update</button></td>
							<td><button type="submit" class="btn btn-danger" name="delete" value="<?php echo $value['unitOfferingID'];?>" >Delete</button></td>
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
