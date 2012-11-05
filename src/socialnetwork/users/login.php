<?php
$page_title = "Login";
$current_page = "login";
include_once('../header.php');
if($_POST['login_submit'] == "Submit") { //form has been submitted
	$username = $_POST['username'];
	$password = $_POST['password'];
	if(trim($username) == "" || trim($password) == "") {
		//echo "Username/Password cannot be blank";
		//TODO: error not working...
		session_start();
		//$_SESSION['error'] = "Username/Password cannot be blank";
		$_SESSION['message'] = "Username/Password cannot be blank";
		header("Location: /sn/users/login.php");	
	} else {	
		$result = pg_exec($dbconn, "select password from person where username='$username'");
		if($numrows = 0) { //user not found
			$_SESSION['message'] = "Username/Password do not match";
			header("Location: /sn/users/login.php");	
		} else {
			$row = pg_fetch_array($result, 0); //0 => first tuple
			//echo "From table : " . $row["password"];
			//echo "From form : " . $password;
			if($password == $row["password"]) {
				//TODO: start session for user
				header("Location: /sn/users/home.php");
			} else {
				$_SESSION['message'] = "Username/Password do not match";
				header("Location: /sn/users/login.php");	
			}
		}
	}
}
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<h2>Login</h2>
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
	<td></td>
	<td><input name="login_submit" type="submit" value="Submit"/><br /></td>
</tr>
</table>
</form>
<?php 
include_once('../footer.php');
?>
