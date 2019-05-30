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
					

					// edit existing user
					if($_POST['submit'] === 'editUser') {
						echo "apply update " . $_POST['email'];
						if(!isset($_POST['roles'])) {
							echo "<script type='text/javascript'>alert('No roles selected');</script>";
						} else {
							$uUser = new User;
							try {
								$uUser->updateUser(
									$_POST['fName'],
									$_POST['lName'],
									$_POST['gender'],
									$_POST['pNum'],
									$_POST['email'],
									$_POST['roles']
								);
							echo "<script type='text/javascript'>alert('User updated successfully!');</script>";
							} catch(mysqli_sql_exception $e) {
								echo "<script type='text/javascript'>alert('{$e->getMessage()}');</script>";
							}
						}
					}

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
							echo "<script type='text/javascript'>alert('User added successfully!');</script>";
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
				
				else 	if(isset($_POST['update'])) {
						//something here ---------------------
						// echo "update " . $_POST['update'];

					// DELETE USER
					} else 	if(isset($_POST['delete'])) {
						
			/* Switch off auto commit to allow transactions*/
		mysqli_autocommit($conn, FALSE);
		$query_success = TRUE;
	
			
			
			// Delete the User
			$stmt = $GLOBALS['conn']->prepare("CALL TCABSDeleateUser(?)");
			$stmt->bind_param("s", $_POST['delete']);
			
			try {
				$stmt->execute();
				
				mysqli_commit($conn);
				echo "<script type='text/javascript'>alert('User deleted successfully');</script>";
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

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Manage Users - TCABS</title>
		<!-- Stylesheets -->
		<?php include "../styles/stylesheet.php"; ?>
  </head>

   <body class="loggedin">
		<?php include "../views/header.php"; ?>
		<div class="content">
			<h2>Manage Users</h2><h2-date><?php echo date('d F, Y (l)'); ?></h2-date><br>
		<div>
		
		<!-- Nav tabs -->
		<?php 
		//show tabs if not in update mode
				if(!isset($_POST['update'])) {
			?>
		<ul class="nav nav-tabs">
  		<li class="nav-item">
    		<a class="nav-link <?php if((isset($_POST['submit']) && $_POST['submit'] == 'addUser') || $_SERVER['REQUEST_METHOD'] == 'GET') { echo 'active';} ?>" data-toggle="tab" href="#home">Add</a>
			</li>
			<li class="nav-item">
				<a class="nav-link <?php if(isset($_POST['submit']) && $_POST['submit'] == 'bulkImport') { echo 'active';} ?>" data-toggle="tab" href="#menu1">Bulk Import via CSV</a>
			</li>
			<li class="nav-item">
				<a class="nav-link <?php if(isset($_POST['submit']) && $_POST['submit'] == 'search') { echo 'active';} ?>" data-toggle="tab" href="#menu2">Update/Delete</a>
			</li>
		</ul>
		<?php  } ?>

		<!-- Tab panes -->
		<div class="tab-content">
		
			<!-- Edit User only when Update button pressed -->
			<?php 
				if(isset($_POST['update'])) {
				$nUser = new User;	
				$searchResults = $nUser->searchUser("%{$_POST['update']}%");
				// print_r($searchResults);
				foreach($searchResults as $key => $value) {
								$name = $value['fName'] . " " . $value['lName'];
								$email = $value['email'];
			?>
			<div>
			<form action="registerUser.php" method="post" class="was-validated"><br>
  		  <p class="h4 mb-4 text-center">Edit User (<?php echo ($_POST['update']); ?>)</p>
		  		<input type="text" id="email" name="email" class="form-control" value="<?php echo $value['email']; ?>"  readonly><br>
				<input type="text" id="fName" name="fName" class="form-control" value="<?php echo $value['fName']; ?>"  required><br>
 	 	  	<input type="text" id="lName" name="lName" class="form-control" value="<?php echo $value['lName']; ?>" required><br>
				<select class="browser-default custom-select" id="gender" name="gender" >
 	 		 	<option selected hidden value="<?php echo $value['gender']; ?>"><?php echo $value['gender']; ?></option>
 	 		   	<option value="M">Male</option>
 	 		   	<option value="F">Female</option>
 	 		 	</select><br><br>
				<input type="text" id="pNum" name="pNum" class="form-control" value="<?php echo str_replace('-','',$value['pNum']); ?>" aria-describedby="defaultRegisterFormPhoneHelpBlock" required><br>
   		 	<input type="email" id="email" name="email" class="form-control" value="<?php echo $value['email']; ?>" required><br>
	
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
  			<button class="btn btn-info my-4 btn-block" type="submit" name="submit" value="editUser">Edit User</button>
			</form>
			</div>
			<?php  } ?>
			<?php  } ?>
			

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
  			<button class="btn btn-info my-4 btn-block" type="submit" name="submit" value="addUser">Register User</button>
			</form>
		</div>
		
		<!-- Tab 2 -->	
  	<div class="tab-pane container fade <?php if(isset($_POST['submit']) && $_POST['submit'] == 'bulkImport') { echo 'active show';} ?>" id="menu1">
  		<br>
  	  	<p class="h4 mb-4 text-center">Bulk Import via CSV</p>
		<div class="col-mb-4 text-center"> 
		<a class="btn btn-outline-info" href="../csv/users.csv" role="button">CSV Template Download</a>
		</div>
		<br><br>
  			<div class="form-group">
      <div class="custom-file">
	  <form action="importCSVUser.php" method="post" enctype="multipart/form-data">
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
		<div class="tab-pane container fade <?php if(isset($_POST['submit']) && $_POST['submit'] == 'search') { echo 'active show';} ?>" id="menu2">
			<form action="registerUser.php" method ="post" class="was-validated"><br>
  	  	<p class="h4 mb-4 text-center">Update/Delete User</p>
				<div class="search-box">
  <input type="text" id="searchQuery" name="searchQuery" class="form-control" placeholder="Enter Name or Email" required>
  
    		<button class="btn btn-info my-4 btn-block" type="submit" name="submit" value="search">Search</button>
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
							if ($searchResults == NULL) {
							echo "<script type='text/javascript'>alert('Oops nothing found!');</script>";
							} else {
							foreach($searchResults as $key => $value) {
								$name = $value['fName'] . " " . $value['lName'];
								$email = $value['email'];
						?>

						<tr style="border-top: 1px solid lightgrey;">
							<td><?php echo $name;?></td>
							<td><?php echo $email;?></td>
							<td><button type="submit" class="btn btn-primary" name="update" value="<?php echo $email;?>" >Update</button></td>
							<td><button type="submit" class="btn btn-danger" name="delete" value="<?php echo $email;?>" >Delete</button></td>
							
						</tr>

							<?php  }}?>

					</table>
				</form><br>
			</div>
			<?php  }?>
			
			</div>
		</div>
		</div>
		</div>
	</body>
  <?php include "../views/footer.php";  ?>  
</html>