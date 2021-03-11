<head>
  <meta charset="utf-8">
	<title>Ping</title>
  <link rel= "stylesheet" type="text/css" href="ping.css">
</head>
<?php
  include 'util/global.php';
  include 'util/db.php';
	//include 'util/topnav.php'; 
  
	global $con;
	
	$con = connectDB($domainNameGlobal,$dbuserGlobal,$dbpasswordGlobal,$schemaGlobal);
	
	session_start();
	
	$error='';
   
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		// username and password sent from form 
		
		$myorgCode = mysqli_real_escape_string($con,$_POST['orgCode']);
		$myusername = mysqli_real_escape_string($con,$_POST['username']);
		$mypassword = mysqli_real_escape_string($con,$_POST['password']); 
		
		echo $mypassword;

		
		$sql = "SELECT password FROM user INNER JOIN organisation ON organisationID = organisationFkID ".
		       " WHERE username = '$myusername' and orgCode ='$myorgCode'";
			//		 echo $sql;
		$result = mysqli_query($con,$sql);
		//echo $sql;
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		//$active = $row['active'];
		
		$count = mysqli_num_rows($result);
		//echo $count;
		// If result matched $myusername and $mypassword, table row must be 1 row
    //echo $row['password'].'<br>';
		//echo "XXX:".password_verify($mypassword,$row['password']);
		if($count == 1 && ($row['password']==$mypassword || password_verify($mypassword,$row['password']))) {
			 //session_register("myusername");
			 $_SESSION['login_user'] = $myusername;
			 
			 header("location: ping_url2.php");
		}else {
			 $error = "Your Login Name or Password is invalid";
		}
	}
?>
   
   <body bgcolor = "#FFFFFF">
	   <div align = "center">
		 <h1>IWI-Ping</h1>
		 <br><br>
	
      
         <div style = "width:350px; border: solid 1px #333333; " align = "left">
            <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Login</b></div>
				
            <div style = "margin:30px">
               <table>
               <form action = "" method = "post">
							    <div><label >Organisation :</label></div><div><input size="30" type = "text" name = "orgCode" class = "box"/></div><br>
                  <div><label >UserName    :</label></div><div><input size="30"  type = "text" name = "username" class = "box"/></div><br>
                  <div><label >Password    :</label></div><div><input size="31"  type = "password" name = "password" class = "box" /></div><br>
                  <input type = "submit" value = " Submit "/><br />
               </form>
							 </table>
               
               <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
					
            </div>
				
         </div>
			
      </div>

   </body>
</html>