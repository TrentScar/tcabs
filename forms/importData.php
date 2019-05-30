<?php
// Load the database configuration file
	require_once("../classes.php");
	session_start();
if(isset($_POST['importSubmit'])){
    // Allowed mime types
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

    // Validate whether selected file is a CSV file
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
        // If the file is uploaded
        if(is_uploaded_file($_FILES['file']['tmp_name'])){

            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

            // Skip the first line
            fgetcsv($csvFile);

            // Parse data from CSV file line by line
            while(($line = fgetcsv($csvFile)) !== FALSE){
                // Get row data
                $fName   = $line[0];
                $lName   = $line[1];
                $gender  = $line[2];
                $pNum    = "0" . $line[3];
                $email   = $line[4];
                $pwd     = $line[5];

                // $salt = "tcabs";
              	$password_encrypted = sha1($pwd);

                // Check whether member already exists in the database with the same email
                $prevQuery = "SELECT email FROM users WHERE email = '".$line[4]."'";
                $prevResult = $conn->query($prevQuery);

                if($prevResult->num_rows > 0){
                    // Update member data in the database
                    $conn->query("UPDATE users SET fName = '".$fName."', lName = '".$lName."', gender = '".$gender."', pNum = '".$pNum."', email = '".$email."', pwd = '".$password_encrypted."'  WHERE email = '".$email."'");
                }else{
                    // Insert member data in the database
                    $conn->query("INSERT INTO users (fName, lName, gender, pNum, email, pwd) VALUES ('".$fName."','".$lName."','".$gender."','".$pNum."','".$email."','".$password_encrypted."')");
                }
            }

            // Close opened CSV file
            fclose($csvFile);

            $qstring = '?status=succ';
        }else{
            $qstring = '?status=err';
        }
    }else{
        $qstring = '?status=invalid_file';
		$_SESSION['csvStatus'] == '1111';
    }
}

// Redirect to the listing page
header("Location: registerUser.php".$qstring);
