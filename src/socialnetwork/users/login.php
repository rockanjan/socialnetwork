<?php
$page_title = "Login";
$current_page = "login";
include_once('../header.php');
if(isset($_SESSION['uid'])) {
	header("Location: /sn/users/home.php");
	exit();
}
if($_POST['login_submit'] == "Submit") { //form has been submitted
	$username = $_POST['username'];
	$password = $_POST['password'];
	if(trim($username) == "" || trim($password) == "") {
		//echo "Username/Password cannot be blank";
		$_SESSION['err'] = "Username/Password cannot be blank";
		session_write_close();
		header("Location: /sn/users/login.php"); //redirect
		exit();
	} else {	
		$result = pg_exec($dbconn, "select * from person where username='$username'");
		$numrows = pg_num_rows($result);
		if($numrows = 0) { //user not found
			$_SESSION['err'] = "Username/Password do not match";
			session_write_close();
			header("Location: /sn/users/login.php");
			exit();	
		} else {
			$row = pg_fetch_array($result, 0); //0 => first tuple
			//echo "From table : " . $row["password"];
			//echo "From form : " . $password;
			if($password == $row["password"]) {
				session_destroy();
				session_start();
				$_SESSION['uid'] = $row["personid"];
				$_SESSION['uname'] = trim($username);
				$_SESSION['name'] = $row["firstname"] . " " . $row["lastname"];
				$imagepath = $row["imagepath"];
				if($imagepath == null || $imagepath == '') {
					$_SESSION['image'] = '../images/defaultuser.png';
				} else {
					$_SESSION['image'] = $imagepath;
				}
				session_write_close();
				header("Location: /sn/users/home.php");
				exit();
				
			} else {
				$_SESSION['err'] = "Username/Password do not match";
				session_write_close();
				header("Location: /sn/users/login.php");
				exit();
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
	<td><input name="login_submit" type="submit" value="Submit" class="button"/><br /></td>
</tr>
</table>
</form>

<p> Do not have an account yet? Register <a href="register.php"> here </a>
<?php 
include_once('../footer.php');
?>
