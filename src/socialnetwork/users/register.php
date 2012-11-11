<?php
$page_title = "Register";
$current_page = "home";
include_once('../header.php');
if(isset($_SESSION['uid'])) {
	header("Location: /sn/users/home.php");
	exit();
}
if(array_key_exists('register_submit', $_POST)) { //form has been submitted
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
		$gender = true;
	} else {
		$gender = false;
	}
	//TODO: perform validation
	$result = pg_exec($dbconn, "select * from person where username='$username'");
	$numrows = pg_num_rows($result);
	if($numrows != 0) {
		$_SESSION['err'] = 'Username ' . $username . ' has already been taken';
		header("Location: /sn/users/register.php");
		exit();
	}
	//save the image
	$imagepath = null;
	$file = str_replace(' ', '_', $_FILES['image']['name']);
	if($file != null) {
		$permitted = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png');
		if(! in_array($_FILES['image']['type'], $permitted) ) {
			$_SESSION['err'] = "Please provide an image file";
			header("Location: /sn/users/register.php");
			exit();
		}
		if(! $_FILES['image']['error']) {
			$extension = end(explode(".", $_FILES['image']['name']));
			$savename = $username . "." . $extension;
			$imagepath =  $imagedir . $savename;
			if(! move_uploaded_file($_FILES['image']['tmp_name'], $imagepath)) {
				$_SESSION['err'] = "There was an error uploading the file, please try again!";
			}
		}
	}
	
	$isactive = true;
	//database operation
	if($username == null || $username == '') {
		$_SESSION['err'] .= "username is required.";
	}
	if($password == null || $password == '') {
		$_SESSION['err'] .= "\npassword is required.";
	}
	if($firstname == null || $firstname == '') {
		$_SESSION['err'] .= "\nfirstname is required.";
	}
	if($lastname == null || $lastname == '') {
		$_SESSION['err'] .= "\nlastname is required.";
	}
	if($_SESSION['err'] != null || $_SESSION['err'] != '') {
		header("Location: /sn/users/register.php");
		exit();
	}
	$query = "INSERT INTO person (username, password, firstname, lastname, address, birthday, gender, isactive, imagepath) " .
	 " VALUES ('$username', '$password', '$firstname', '$lastname', '$address', '$birthday', '$gender', '$isactive', '$imagepath')";
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
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
<h2>Registration</h2>
<p> * are required fields </p>
<table>
<tr>
	<td><label>Username: </label></td>
	<td><input type="text" name="username" /> * </td>
</tr>
<tr>
	<td><label>Password :</label></td>
	<td><input type="password" name="password"/> * <br/></td>
</tr>
<tr>
	<td><label>First Name:</label></td>
	<td><input type="text" name="firstname"/> * </td>
</tr>
<tr>
	<td><label>Last Name:</label></td>
	<td><input type="text" name="lastname"/> * </td>
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
		<input type="radio" name="sex" value="male" checked />Male <br>
		<input type="radio" name="sex" value="female" />Female
	</td>
</tr>
<tr>
	<td><label>Photo</label></td>
	<td>
		<input type="file" name="image" /><br>
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