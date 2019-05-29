<?php
	require_once("../classes.php");
	session_start();
	if (!isset($_SESSION['logged_in'])) {
		header('Location: /tcabs/login.php');
	} else {

		// check user permission to access the page(admin)
		if(!$_SESSION['loggedUser']->uRoles['convenor']) {
			header('Location: /tcabs/dashboard.php');
		} else {

			// if there was a post request
			if($_SERVER['REQUEST_METHOD'] == 'POST') {

				// if button presses with name attribute = submit
				if(isset($_POST['submit'])) {

					// if Add Single User submit button pressed
					if($_POST['submit'] === "") {

						//	$nUser = new User;
							try {

								//$nUser->registerUser(
//								);
						//		echo "<script type='text/javascript'>alert('');</script>";
							} catch(mysqli_sql_exception $e) {
								echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
							}
						}

					// if bulk import form button pressed
					} else if($_POST['submit'] === "bulkImport") {

						try {
							$row = parse_csv_file($_FILES['csvFile']['tmp_name']);
							$teamObj = new Team;

							$i = 1;
							foreach($row as $index => $dataArr) {
								try {

									// problem with csv file rollback because registerUser uses commit
									//);

								} catch(mysqli_sql_exception $e) {
									echo "<script type='text/javascript'>alert('Row {$i} : {$e->getMessage()}');</script>";
									throw new Exception('Please use valid data in csv file!');
								}
								$i = $i +1;
							}
							
						} catch(Exception $e) {
							echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
						}
			
					// if search form submit button pressed
					} else if($_POST['submit'] === "search") {

						$teamObj = new teamObj;
						try {

							// returns a multidimensional array for each user found
							//$searchResults = $nUser->searchUser("%{$_POST['searchQuery']}%");

						} catch(mysqli_sql_exception $e) {
							echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
						}

					}
				}
			}
		}
	//}
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
			<h2>Register Teams</h2><h2-date><?php echo date('d F, Y (l)'); ?></h2-date><br>
		<div>
		
		<!-- Nav tabs -->
		<ul class="nav nav-tabs">
  		<li class="nav-item">
    		<a class="nav-link <?php if((isset($_POST['submit']) && $_POST['submit'] == 'addTeam') || $_SERVER['REQUEST_METHOD'] == 'GET') { echo 'active';} ?>" data-toggle="tab" href="#home">Add</a>
			</li>
			<li class="nav-item">
				<a class="nav-link <?php if(isset($_POST['submit']) && $_POST['submit'] == 'bulkImport') { echo 'active';} ?>" data-toggle="tab" href="#menu1">Bulk Import via CSV</a>
			</li>
			<li class="nav-item">
				<a class="nav-link <?php if(isset($_POST['submit']) && $_POST['submit'] == 'search') { echo 'active';} ?>" data-toggle="tab" href="#menu2">Search</a>
			</li>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">

		<!-- Tab 1 -->
  	<div class="tab-pane container <?php if((isset($_POST['submit']) && $_POST['submit'] == 'addTeam') || $_SERVER['REQUEST_METHOD'] == 'GET') { echo 'active show';} ?>" id="home">
			<form action="registerTeam.php" method ="post" class="was-validated"><br>
  		  <p class="h4 mb-4 text-center">Add Team</p>
				<input type="text" id="teamID" name="teamID" class="form-control" placeholder="Team ID" required><br>
 	 	  	<input type="text" id="teamName" name="teamName" class="form-control" placeholder="Team Name" required><br>
 	 	  	<input type="text" id="staffID" name="offeringStaffID" class="form-control" placeholder="Offering Staff ID" required><br>
 	 	  	<input type="text" id="projManager" name="projManager" class="form-control" placeholder="Project Manager Name" required><br><br>
	
  			<button class="btn btn-info my-4 btn-block" type="submit" name="submit" value="addTeam">Register Team</button>
			</form>
		</div>
		
		<!-- Tab 2 -->	
  	<div class="tab-pane container fade <?php if(isset($_POST['submit']) && $_POST['submit'] == 'bulkImport') { echo 'active show';} ?>" id="menu1">
  		<form action="registerTeam.php" method ="POST" enctype="multipart/form-data" class="was-validated"><br>
  	  	<p class="h4 mb-4 text-center">Bulk Import via CSV</p>
  			<div class="form-group">
    			<label for="csvFile">Please choose a CSV file to upload</label>
   			 	<input type="file" name="csvFile" class="form-control-file" id="csvFile">
					<input type="hidden" name="MAX_FILE_SIZE" value="30000" />
  			</div>
  			<button class="btn btn-info my-4 btn-block" type="submit" name="submit" value="bulkImport">Register Teams</button>
			</form>
		</div>
		
		<!-- Tab 3 -->
		<div class="tab-pane container fade <?php if(isset($_POST['submit']) && $_POST['submit'] == 'search') { echo 'active show';} ?>" id="menu2">
			<form action="registerTeam.php" method ="post" class="was-validated"><br>
  	  	<p class="h4 mb-4 text-center">Update/Delete Team</p>
				<div class="search-box">
					<input type="text" name="searchQuery" autocomplete="off" placeholder="Enter Team Name" />
  				<button class="btn btn-primary" name="submit" value="search">Search</button>
					<div class="result"></div>
				</div>
			</form><br>

			<!-- Show Search Results -->
			<?php 
				if(isset($_POST['submit']) && $_POST['submit'] == 'search') {
			?>		

			<div>
				<form action="manageTeams.php" method="post">
					<table style="width: 100%;">
						<tr>
    					<th style="width: 40%;">Team ID</th>
							<th style="width: 35%;">Team Name</th>
							<th style="width: 15%;"></th>
    					<th style="width: 15%;"></th>
    				</tr>

						<?php 

							foreach($searchResults as $key => $value) {
								$tName = $value['tName'];
								$teamID = $value['tID'];
						?>

						<tr style="border-top: 1px solid lightgrey;">
							<td><?php echo $tName;?></td>
							<td><?php echo $teamID;?></td>
							<td><button type="submit" class="btn btn-primary" name="update" value="<?php echo $teamID;?>" >Update</button></td>
							<td><button type="submit" class="btn btn-danger" name="delete" value="<?php echo $teamID;?>" >Delete</button></td>
						</tr>

						<?php  }?>

					</table>
				</form><br>
			</div>
			<?php  }?>
  	<?php include "../views/footer.php";  ?>  
	</body>
</html>
