<?php
	require_once("../classes.php");
	session_start();
	if (!isset($_SESSION['logged_in'])) {
		header('Location: /tcabs/login.php');
	} else {

		// check user permission to access the page(admin)
		if(!$_SESSION['loggedUser']->uRoles['admin']) {
			header('Location: /tcabs/dashboard.php');
		} else {

			// if there was a post request
			if($_SERVER['REQUEST_METHOD'] == 'POST') {

				// if button presses with name attribute = submit
				if(isset($_POST['submit'])) {

					// if Add Single User submit button pressed
					if($_POST['submit'] === "addUser") {

						// validate if check boxes are ticked and no combination with student
						if(!isset($_POST['roles'])) {
							echo "<script type='text/javascript'>alert('No roles selected');</script>";
						} else {
							$nUser = new User;
							try {
								$nUser->registerUser(
									$_POST['fName'],
									$_POST['lName'],
									$_POST['gender'],
									$_POST['pNum'],
									$_POST['email'],
									$_POST['pwd'],
									$_POST['roles']
								);
							} catch(mysqli_sql_exception $e) {
								echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
							}
						}

					// if bulk import form button pressed
					} else if($_POST['submit'] === "bulkImport") {

						try {
							$row = parse_csv_file($_FILES['csvFile']['tmp_name']);
							$userObj = new User;

							$i = 1;
							foreach($row as $index => $dataArr) {
								try {

									// problem with csv file rollback because registerUser uses commit
									$userObj->registerUser(
										$dataArr['fName'], 
										$dataArr['lName'], 
										$dataArr['gender'], 
										$dataArr['pNum'], 
										$dataArr['email'], 
										$dataArr['pwd'],
										array(0 => 'nullUser')
									);

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

						$nUser = new User;
						try {

							// returns a multidimensional array for each user found
							$searchResults = $nUser->searchUser("%{$_POST['searchQuery']}%");

						} catch(mysqli_sql_exception $e) {
							echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
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
			<h2>User Administration</h2><h2-date><?php echo date('d F, Y (l)'); ?></h2-date><br>
		<div>
		
		<!-- Nav tabs -->
		<ul class="nav nav-tabs">
  		<li class="nav-item">
    		<a class="nav-link <?php if((isset($_POST['submit']) && $_POST['submit'] == 'addUser') || $_SERVER['REQUEST_METHOD'] == 'GET') { echo 'active';} ?>" data-toggle="tab" href="#home">Add</a>
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
  	<div class="tab-pane container <?php if((isset($_POST['submit']) && $_POST['submit'] == 'addUser') || $_SERVER['REQUEST_METHOD'] == 'GET') { echo 'active show';} ?>" id="home">
			<form action="registerUser.php" method ="post" class="was-validated"><br>
  		  <p class="h4 mb-4 text-center">Add User</p>
				<input type="text" id="fName" name="fName" class="form-control" placeholder="First Name" required><br>
 	 	  	<input type="text" id="lName" name="lName" class="form-control" placeholder="Last Name" required><br>
				<select class="browser-default custom-select" id="gender" name="gender" required>
 	 		 		<option value="" disabled="" selected="">Gender</option>
 	 		   	<option value="M">Male</option>
 	 		   	<option value="F">Female</option>
 	 		 	</select><br><br>
				<input type="text" id="pNum" name="pNum" class="form-control" placeholder="Phone number" aria-describedby="defaultRegisterFormPhoneHelpBlock" required><br>
   		 	<input type="email" id="email" name="email" class="form-control" placeholder="E-mail" required><br>
   		 	<input type="password" id="pwd" name="pwd" class="form-control" placeholder="Password" aria-describedby="defaultRegisterFormPasswordHelpBlock" required><br>
	
				<p>User Roles</p>
				<div class="row">
					<div class="col">
						<label class="checkbox-inline"><input type="checkbox" name="roles[]" value="admin"> Admin</label>
   		 		</div>
					<div class="col">
  					<label class="checkbox-inline"><input type="checkbox" name="roles[]" value="convenor"> Convenor</label>
					</div>
					<div class="col">
   					<label class="checkbox-inline"><input type="checkbox" name="roles[]" value="supervisor"> Supervisor</label>
    			</div>
					<div class="col">
    				<label class="checkbox-inline"><input type="checkbox" name="roles[]" value="student"> Student</label>
    			</div>
				</div>
  			<button class="btn btn-info my-4 btn-block" type="submit" name="submit" value="addUser">Register Unit</button>
			</form>
		</div>
		
		<!-- Tab 2 -->	
  	<div class="tab-pane container fade <?php if(isset($_POST['submit']) && $_POST['submit'] == 'bulkImport') { echo 'active show';} ?>" id="menu1">
  		<form action="registerUser.php" method ="POST" enctype="multipart/form-data" class="was-validated"><br>
  	  	<p class="h4 mb-4 text-center">Bulk Import via CSV</p>
  			<div class="form-group">
    			<label for="csvFile">Please choose a CSV file to upload</label>
   			 	<input type="file" name="csvFile" class="form-control-file" id="csvFile">
					<input type="hidden" name="MAX_FILE_SIZE" value="30000" />
  			</div>
  			<button class="btn btn-info my-4 btn-block" type="submit" name="submit" value="bulkImport">Register Units</button>
			</form>
		</div>
		
		
		<!-- Tab 3 -->
		<div class="tab-pane container fade <?php if(isset($_POST['submit']) && $_POST['submit'] == 'search') { echo 'active show';} ?>" id="menu2">
			<form action="registerUser.php" method ="post" class="was-validated"><br>
  	  	<p class="h4 mb-4 text-center">Update/Delete User</p>
				<div class="search-box">
					<input type="text" name="searchQuery" autocomplete="off" placeholder="Enter User Email or Name" />
  				<button class="btn btn-primary" name="submit" value="search">Search</button>
					<div class="result"></div>
				</div>
			</form><br>

			<!-- Show Search Results -->
			<?php 
				if(isset($_POST['submit']) && $_POST['submit'] == 'search') {
			?>		

			<div>
				<form action="registerUser.php" method="post">
					<table style="width: 100%;">
						<tr>
    					<th style="width: 40%;">Name</th>
							<th style="width: 35%;">Email</th>
							<th style="width: 15%;"></th>
    					<th style="width: 15%;"></th>
    				</tr>

						<?php 

							foreach($searchResults as $key => $value) {
								$name = $value['fName'] . $value['lName'];
								$email = $value['email'];
						?>

						<tr style="border-top: 1px solid lightgrey;">
							<td><?php echo $name;?></td>
							<td><?php echo $email;?></td>
							<td><button type="submit" class="btn btn-primary" name="update" value="<?php echo $resultEmail;?>" >Update</button></td>
							<td><button type="submit" class="btn btn-danger" name="delete" value="<?php echo $resultEmail;?>" >Delete</button></td>
						</tr>

						<?php  }?>

					</table>
				</form><br>
			</div>
			<?php  }?>
  	<?php include "../views/footer.php";  ?>  
	</body>
</html>
