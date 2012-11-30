<div>
<img src="<?php echo $_SESSION['image']; ?>" style="max-width: 100px; max-height: 100px;"/>&nbsp; &nbsp;
</div>

<div id="userwelcome"> <?php echo "Hello, " . $_SESSION['name']; ?></div>
<div class="clear"></div>
<div>
<a href="viewalbum.php">View Albums</a> &nbsp; &nbsp;
<a href="createalbum.php">Create new album</a> &nbsp; &nbsp;
<a href="addphoto.php">Add photos </a> &nbsp; &nbsp;
<a href="searchphoto.php">Search photos </a> &nbsp; &nbsp;
<!-- <a href="#">Search Friends</a> &nbsp; &nbsp; -->
<a href="logout.php">Logout</a>
</div>
<hr />