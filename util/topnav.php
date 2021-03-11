 <div class="topnav">
  <a href="ping_url2.php">URL</a>
  <a href="ping_run.php">Run</a>
	<a href="user.php">User</a>
	<a href="logout.php">Logout</a>
<?php
  $sql = 'select orgCode,organisationID from user inner join organisation on organisationID = organisationFkID '.
	       ' where username="'.$_SESSION['login_user'].'"';
  $result = mysqli_query($con,$sql);
	$row = $result->fetch_assoc();
	echo '<a><b>'.$row['orgCode'].'</b></a>';
	$organisationID = $row['organisationID'];

?>
</div> 