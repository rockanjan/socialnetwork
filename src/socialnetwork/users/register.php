<?php
$page_title = "Register";
$current_page = "home";
include_once('../header.php');
if($_POST['register_submit'] == "Submit") { //form has been submitted
	$username = $_POST['username'];
	$password = $_POST['password'];
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$address = $_POST['address'];
	$birthday = $_POST['birthday'];
	if($birthday=="") {
		$birthday = "0000-00-00";
	}
	$gender = $_POST['sex'];
	if( strcmp(strtolower($gender),"male") == 0) {
		$gender = "1";
	} else {
		$gender = "0";
	}
	
	//echo $username . $password . $gender;
	
	//TODO: perform validation
	
	//database operation
	$query = "INSERT INTO person (username, password, firstname, lastname, address, birthday, gender, isactive, imagepath) " .
	 " VALUES ('$username', '$password', '$firstname', '$lastname', '$address', '$birthday', '$gender', '1', '')";
	$result = pg_query($query);
	if(! $result) {
		die('Error saving in database. Query failed: ' . pg_last_error());
	} else {
		pg_free_result($result);
		session_start();
		$_SESSION['message'] = "Congratulations! You are registered. Start by logging in.";
		header("Location: /sn/users/login.php");
	}
}
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<h2>Registration</h2>
<table>
<tr>
	<td><label>Username: </label></td>
	<td><input type="text" name="username"/></td>
</tr>
<tr>
	<td><label>Password :</label></td>
	<td><input type="password" name="password"/><br/></td>
</tr>
<tr>
	<td><label>First Name:</label></td>
	<td><input type="text" name="firstname"/></td>
</tr>
<tr>
	<td><label>Last Name:</label></td>
	<td><input type="text" name="lastname"/></td>
</tr>
<tr>
	<td><label>Address</label></td>
	<td><input type="text" name="address"/></td>
</tr>
<tr>
	<td><label>Date of Birth</label></td>
	<td><input type="text" name="birthday"/> format : yyyy-mm-dd</td>
</tr>
<tr>
	<td><label>Gender</label></td>
	<td>
		<input type="radio" name="sex" value="male" checked />Male<br>
		<input type="radio" name="sex" value="female" />Female
	</td>
</tr>
<tr>
	<td><label>Photo</label></td>
	<td>
		<input type="file" name="photo" /><br>
	</td>
</tr>
<tr>
	<td></td>
	<td><input name="register_submit" type="submit" value="Submit"/><br /></td>
</tr>
</table>
</form>

<?php 
include_once('../footer.php');
?>