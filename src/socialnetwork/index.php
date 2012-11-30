<?php
$page_title = "Welcome";
$current_page = "home";
include_once('header.php');
if(isset($_SESSION['uid'])) {
	header("Location: /sn/users/home.php");
	exit();
}
?>
<table>
<tr>
<td width="50%" style="padding-right:20px">
<h2>Welcome</h2>
<p>Welcome to <?php echo $sitename; ?></p>
<p>This social networking website mainly focuses on the content of the images for most applications.</p>
<img src="images/social_network.jpg" style="width:400px"/>
</td>
<td id="home_register">
	<form action="users/register.php" method="post" enctype="multipart/form-data">
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
	<td><input name="register_submit" type="submit" value="Submit" class="button"/><br /></td>
</tr>
</table>
</form>
</td>
</tr>
</table>
<?php 
include_once('footer.php');
?>