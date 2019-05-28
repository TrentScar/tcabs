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


					// ernol single student
					if($_POST['submit'] === "enrol") {

						$enrolObj = new Enrolment;
						try {
							$enrolObj->enrolUser($_POST['email'], $_POST['unitCode'], $_POST['term'], $_POST['year']);
							echo "<script type='text/javascript'>alert('Student enrolled successfully!');</script>;";
						} catch(mysqli_sql_exception $e) {
							echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
						}
					} else if($_POST['submit'] === "bulkEnrol") {

						try {
							$row = parse_csv_file($_FILES['csvFile']['tmp_name']);
							$enrolObj = new Enrolment;

							$i = 1;
							foreach($row as $index => $dataArr) {
								try {

									// there is no need of census date in unit table, they should be in unit offering table
									$enrolObj->enrolUser(
										$dataArr['userEmail'], 
										$dataArr['unitCode'], 
										$dataArr['term'], 
										$dataArr['year'], 
									);

								} catch(mysqli_sql_exception $e) {
									throw $e;
								}
								$i = $i +1;
							}
							echo "<script type='text/javascript'>alert('{$i} rows added successfully!');</script>";
						} catch(Exception $e) {
							echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
						}
					} else if($_POST['submit'] == 'search') {

						try {
							$enrolObj = new Enrolment;
							$searchResults = $enrolObj->getAllEnrolments();
						} catch(mysqli_sql_exception $e) {
							echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
						}

					} else if($_POST['submit'] == 'upEnrol') {
						
						$enrolObj = new Enrolment;

						$enrolObj->enrolmentID = $_POST['enrolmentID'];
						$enrolObj->unitOfferingID = $_POST['unitOfferingID'];
						//$enorlObj->sUserName = $_POST['sUserName'];	 //gives error default with empty value
						print_r($enrolObj);

						try {

						} catch(mysqli_sql_exception $e) {
							
						}

					}
				}
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
  </head>

  <body class="loggedin">
		<?php include "../views/header.php"; ?>
		<div class="content">
			<h2>Manage Enrolment</h2><h2-date><?php echo date('d F, Y (l)'); ?></h2-date><br>
		<div>

		<ul class="nav nav-tabs">
  		<li class="nav-item">
    		<a class="nav-link <?php if((isset($_POST['submit']) && $_POST['submit'] == 'enrol') || $_SERVER['REQUEST_METHOD'] == 'GET') { echo 'active';} ?>" data-toggle="tab" href="#home">Enrol Student</a>
  		</li>
  		<li class="nav-item">
    		<a class="nav-link <?php if(isset($_POST['submit']) && $_POST['submit'] == 'bulkEnrol') { echo 'active';} ?>" data-toggle="tab" href="#menu1">Bulk Import via CSV</a>
  		</li>
  		<li class="nav-item">
    		<a class="nav-link <?php if(isset($_POST['submit']) && $_POST['submit'] == 'search') { echo 'active';} ?>" data-toggle="tab" href="#menu2">Veiw Enrolments</a>
  		</li>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">

			<!-- Edit Unit only when Update button pressed -->
			<?php
				if(isset($_POST['upEnrol'])) {
					$enrolObj = new Enrolment;
					$searchResults = $enrolObj->getAllEnrolments();
					foreach($searchResults as $key => $value) {
						if($value['enrolmentID'] == $_POST['upEnrol']) {
							$result = $value;
			?>
			
			<div>
				<form action="enrolStudents.php" method="post" class="was-validated"><br>
					<p class="h4 mb-4 text-center">Edit Enrolment for (<?php echo "({$result['sUserName']})"; ?>)</p>
					<input type="text" id="enrolmentID" name="enrolmentID" class="form-control" value="<?php echo $result['enrolmentID']; ?>" required><br>
					<input type="text" id="unitOfferingID" name="unitOfferingID" class="form-control" value="<?php echo $result['unitOfferingID']; ?>" required><br>
					<input type="text" id="sUserName" name="sUserName" class="form-control" value="<?php echo $result['sUserName']; ?>" required><br><br>
					<button class="btn btn-info my-4 btn-block" type="submit" name="submit" value="upEnrol">Edit Enrolment</button>
				</form>
			</div>
			<?php  } ?>
			<?php  } ?>
			<?php  } ?>

			<!-- Tab 1 -->
  		<div class="tab-pane container <?php if((isset($_POST['submit']) && $_POST['submit'] == 'enrol') || $_SERVER['REQUEST_METHOD'] == 'GET') { echo 'active show';} ?>" id="home">
				<form action="enrolStudents.php" method ="post" class="was-validated"><br/>
  	  		<p class="h4 mb-4 text-center">Enrol Students into a Unit</p>
					<input type="text" id="email" name="email" class="form-control" placeholder="Student Email" required><br>
 	   			<input type="text" id="unitCode" name="unitCode" class="form-control" placeholder="Unit Code" required><br>
 	   			<input type="text" id="year" name="year" class="form-control" placeholder="Enter Year" required><br>
					<select class="browser-default custom-select" id="term" name="term" required>
 	  				<option value="" disabled="" selected="">Select Term</option>
 	    			<option value="Semester 1">Semester 1</option>
 	    			<option value="Semester 2">Semester 2</option>
 	   		 		<option value="Winter">Winter</option>
 	   		 		<option value="Summer">Summer</option>
 	  			</select><br><br>
  				<button class="btn btn-info my-4 btn-block" type="submit" name="submit" value="enrol">Enrol Student</button>
				</form>
			</div>

			<!-- Tab 2 -->
  		<div class="tab-pane container fade <?php if((isset($_POST['submit']) && $_POST['submit'] == 'bulkEnrol')) { echo 'active show';} ?>" id="menu1">
				<form action="enrolStudents.php" method ="post" class="was-validated" enctype="multipart/form-data"><br/>
  	  		<p class="h4 mb-4 text-center">Bulk Import</p>
  				<div class="form-group">
    				<label for="csvFile">Please choose a CSV file to upload</label>
						<input type="hidden" name="MAX_FILE_SIZE" value="30000" />
   			 		<input type="file" name="csvFile" class="form-control-file" id="csvFile">
  				</div>
  				<button class="btn btn-info my-4 btn-block" type="submit" name="submit" value="bulkEnrol">Enrol Students</button>
				</form><br>
			</div>

			<!-- Tab 3 -->
			<div class="tab-pane container fade <?php if(isset($_POST['submit']) && $_POST['submit'] == 'search') { echo 'active show';} ?>" id="menu2">
				<form action="enrolStudents.php" method ="post" class="was-validated"><br>
 		 	  	<p class="h4 mb-4 text-center">All Enrolments</p>
					<div class="search-box">
 		 				<button class="btn btn-primary" name="submit" value="search">Get All Enrolments</button>
						<div class="result"></div>
					</div>
				</form><br>

				<!-- Show Search Results -->
				<?php 
					if(isset($_POST['submit']) && $_POST['submit'] == 'search') {
				?>		

				<div>
					<form action="enrolStudents.php" method="post">
						<table style="width: 100%;">
							<tr>
 		   					<th style="width: 15%;">Email</th>
								<th style="width: 15%;">UnitCode</th>
								<th style="width: 30%;">UnitName</th>
								<th style="width: 10%;">Term</th>
								<th style="width: 5%;">Year</th>
								<th style="width: 10%;"></th>
 		   					<th style="width: 10%;"></th>
 		   				</tr>

							<?php 

								foreach($searchResults as $key => $value) {
									$enrolmentID = $value['enrolmentID'];
									$sUserName = $value['sUserName'];
									$unitCode = $value['unitCode'];
									$unitName = $value['unitName'];
									$term = $value['term'];
									$year = $value['year'];
							?>

							<tr style="border-top: 1px solid lightgrey;">
								<td><?php echo $sUserName;?></td>
								<td><?php echo $unitCode;?></td>
								<td><?php echo $unitName;?></td>
								<td><?php echo $term;?></td>
								<td><?php echo $year;?></td>
								<td><button type="submit" class="btn btn-primary" name="upEnrol" value="<?php echo $enrolmentID;?>" >Update</button></td>
								<td><button type="submit" class="btn btn-danger" name="delEnrol" value="<?php echo $enrolmentID;?>" >Delete</button></td>
							</tr>

							<?php  }?>

						</table>
					</form><br>
				</div>
			<?php  }?>
		</div>
	</body>
  <?php include "../views/footer.php";  ?>  
</html>
