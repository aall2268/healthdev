<?php

function connectDB($domainname, $user,$password,$schema) {
	   //$con = mysqli_connect("www.alles-mit-links.ch","aall23","!AALL23aall23!","usr_web379_1");
		 $con = mysqli_connect($domainname,$user,$password,$schema)  or die ("<script language='javascript'>alert('Unable to connect to database')</script>");

     mysqli_query($con,"set character set utf8;");
		 return $con;
}
		 
function disconnectDB($con) {
		mysqli_close($con);
}

function session() {
	 session_start();
   
   $user_check = $_SESSION['login_user'];
   
   $ses_sql = mysqli_query($db,"select username from user where username = '$user_check' ");
   
   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   
   $login_session = $row['username'];
   
   if(!isset($_SESSION['login_user'])){
      header("location:login.php");
   }
}
?>