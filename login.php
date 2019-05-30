<!-- Login page for users -->
<?php 
	require_once('classes.php');
	session_start();	// initailize session variable

 	if (isset($_SESSION['logged_in'])) {
  	header('Location: dashboard.php');
  	exit();
	} else {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			// User login process check if email and password exists and is correct
			$loginUser = new User;
			$loginUser->getUser($_POST['email']);

			if(!$loginUser->userExist()) {
				echo "<script type='text/javascript'>alert('User does not exist');</script>";
			} else {

				if(!$loginUser->checkPwd($_POST['pwd'])) {
					echo "<script type='text/javascript'>alert('Wrong Password');</script>";
				} else {

					$_SESSION['logged_in'] = TRUE;	// to be checked before displaying dashboard
					$_SESSION['loggedUser'] = $loginUser;

					//print_r($_SESSION['loggedUser']->uRoles);
					//print_r($_SESSION['loggedUser']->uPerms);


					$_SESSION['roles'] = $_SESSION['loggedUser']->roles;

					header("location: dashboard.php"); // login and redirect to main page
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
		<title>Login</title>

		<!-- Stylesheets -->
    <?php include "styles/stylesheet.php"; ?>
  </head>

  <body>

    <div class="login">
       <h1>Team Contribution and <br> Budget System</h1>
       <form action="login.php" method="post">
         <label for="username">
           <i class="fas fa-user"></i>
         </label>
         <input type="text" name="email" placeholder="Enter E-mail" id="username" required>
         <label for="pwd">
           <i class="fas fa-lock"></i>
         </label>
         <input type="password" name="pwd" placeholder="Enter password" id="password" required>
         <input type="submit" value="Login">
       </form>
     </div>

  </body>
</html>
