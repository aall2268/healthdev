<head>
  <meta charset="utf-8">
  <link rel= "stylesheet" type="text/css" href="ping.css">
	<script type ="text/javascript">
function send(ak,id,myname) {
	
	if (ak == 0) document.f.ak.value = "in";
	else if (ak == 1) document.f.ak.value = "up";
	else if (ak == 2) {
		if (confirm("Benutzer "+ myname + " entfernen?"))
			document.f.ak.value = "de";
		else
			return;
	}
	document.f.id.value = id;
	document.f.submit();
}
</script>
</head>
<?php
	include 'util/global.php';
  include 'util/db.php';
	include 'tables/user.php';
	
  global $con;	
	$con = connectDB($domainNameGlobal,$dbuserGlobal,$dbpasswordGlobal,$schemaGlobal);

	include 'util/session.php';	
	include 'util/topnav.php';	
	function selected($optionName,$selectedValue) {
		if ($optionName == $selectedValue) {
			return ' selected ';
		}
    return '';		
	}
	
	function writeColumn2($name,$style) {
		echo '<td style="'.$style.'">'.$name.'</td>';
	}
	
	function writeColumn3($name,$value,$size,$type) {
		//echo '<td style="'.$style.'">'.$name.'</td>';
		echo "<td> <input name='".$name."' type='".$type."' value='".$value."' style='".$size."' ></td>";
		//"<td><input name='urlName[".$urlID."]' value='".$obj->urlName . "' size='40'></td>"
	}
	
	$query = 'select type from user '.
	       ' where username="'.$_SESSION['login_user'].'"';
  $result = mysqli_query($con,$query);
	$row = $result->fetch_assoc();
	$type = $row['type'];	
		/* Aktion ausführen */
	if (isset($_POST["ak"])) {
		/* neu eintragen */
		if ($_POST["ak"]=="in") {
			$sql = "INSERT INTO user" 
			      ."(organisationFkID,username,password,type,email,pingemail) values ("
						. $organisationID . ", '"
						. $_POST["username"][0] . "', '"
						. password_hash($_POST["password"][0],PASSWORD_BCRYPT) . "', '"
						. $_POST["type"][0] . "', '"
						. $_POST["email"][0] . "', '"
						. $_POST["pingemail"][0] . "' )";
			//echo $sql."   X";
			mysqli_query($con,$sql);	
		}
	
	  /* ändern */
		else if ($_POST["ak"]=="up") {
			$id = $_POST["id"];
			
			//echo 'XXX'.$_POST["urlID"];
			$query = "UPDATE user "
							."   SET username='".$_POST['username'][$id]."', "
							."       email='".$_POST['email'][$id]."', ";
			if ($type=='Admin') {
				 $query = $query."       type='".$_POST['type'][$id]."', ";
			}
			$query = $query."       pingemail='".$_POST['pingemail'][$id]."', "
							."       updatedAt=CURRENT_TIMESTAMP, "
							."       updatedBy='".$_POST['userID'][$id]."' "
							." WHERE userID ='".$_POST['userID'][$id]."'"
							."   AND organisationFkID =".$organisationID;
							
			//echo $query;
			mysqli_query($con,$query);	
		}
		
		/* löschen */
		else if ($_POST["ak"]=="de") {
			$query = "DELETE FROM user WHERE userID = " . $_POST["id"];
			//echo $query;
			mysqli_query($con,$query);	
		}				
	
	}
	
	//echo $organisationID;


	echo '<body>';
	
	echo '<br>';
	
	//echo '<form action="user.php" method="post">';
	/* Forumalbeginn */
	echo "<form name='f' action='user.php' method='post'>";
	echo "<input name='ak' type='hidden'>";
	echo "<input name='id' type='hidden'>";

	echo '<table border>';
  echo '<thead>';
  echo '<tr>';
	echo '<th align="left" style="width: 200px; padding:0;">Username</th>';
	if ($type=='Admin') {
			echo '<th align="left" style="width: 200px; padding:0;">Password</th>';	
      echo '<th align="left" style="width: 50px; padding:0;">Type</th>';			
	}
	echo '<th align="left" style="width: 300px; padding:0;">EMail</th>';
	echo '<th align="left" style="width: 50px; padding:0;">Ping</th>';
	echo '<th></th>';
	//echo '<th align="left" style="width: 130px; padding:0;">updatedAt</th>';
  //echo '<th align="left" style="width: 50px; padding:0;">updatedBy</th>';
  //echo '<th align="left" style="width: 130px; padding:0;">createdAt</th>';
  //echo '<th align="left" style="width: 50px; padding:0;">createdBy</th>';
	echo '</tr>';
	echo '</thead>';

	echo '<tbody>';
/* Neuer 	Eintrag */
	echo "\n\n<tr>" 
	   //. "<td><input name='userID[0]' type='hidden'></td>"
	   . "<td><input name='username[0]' style='width: 200px; padding:0;'></td>";
  if ($type=='Admin') {
			echo "<td><input name='password[0]' type='password' style='width: 200px; padding:0;'></td>";	
      echo "<td><select name='type[0]'><option value='Admin' >Admin</option><option value='User'>User</option></select></td>";			
	}
	echo "<td><input name='email[0]' style='width: 300px; padding:0;'></td>"
	   . "<td><select name='pingemail[0]'><option value='Ja' >Ja</option><option value='Nein'>Nein</option></select></td>"		
	   //. "<td><input name='pingemail[0]' style='width: 50px; padding:0;'></td>"
		 //. "<td><select name='urlStatus[0]'><option value='R' >Run</option><option value='I'>Inactive</option></select></td>"
     . "<td><a href='javascript:send(0,0);'>neu eintragen</a></td>"	
     . "</tr>";	
	
	$query = 'select userID, '.
	         '       organisationFkID,'.
           '       username, '.
					 '       password, '.
					 '       type, '.
           '       email, '.
           '       pingemail, '.
           '       updatedAt, '.
           '       updatedBy, '.
           '       createdAt,' .
           '       createdBy ' .
					 '  from user '.
					 ' where organisationFkID ='.$organisationID;
					 
	//echo $query;
	
	if ($type!='Admin') {
		$query = $query .'  and username="'.$login_session.'"';
	}
		
  if ($result = mysqli_query($con,$query)) {
    $obj_array = array();
		$i = 0;
		
		while ($obj = $result->fetch_object('user')) {
			 $userID = $obj->userID;
			 $id = $userID;
			 echo '<tr>';	
       echo '<input type="hidden" id="userID" name="userID['.$userID.']" value="'.$obj->userID.'">';	
			 writeColumn3('username['.$userID.']',$obj->username,'width:200px','text');
			 if ($type=='Admin') {
					//writeColumn3('password['.$userID.']',$obj->password,'width:200px','password');
					echo '<td><a href="password.php?user='.$obj->username.'" target="Passwort" onclick="window.open(&quot;&quot;,&quot;Passwort&quot;,&quot;top=50,screenX=50,left=100,screenY=100,height=250,width=250&quot; )">Passwort ändern</a> </td>';
          echo "<td><select name='type[".$userID."]'><option value='Admin' ";
			    echo selected('Admin',$obj->type);
				  echo " >Admin</option><option value='User' ";
				  echo selected("User",$obj->type);
				  echo ">User</option></select></td>";
			 }
			 writeColumn3('email['.$userID.']',$obj->email,'width:300px','text');
			 //writeColumn3('pingemail['.$userID.']',$obj->pingemail,'width:50px','text');$
			 echo "<td><select name='pingemail[".$userID."]'><option value='Ja' ";
			 echo selected('Ja',$obj->pingemail);
			 echo " >Ja</option><option value='Nein' ";
			 echo selected("Nein",$obj->pingemail);
			 echo ">Nein</option></select></td>";
			 echo "<td><a href='javascript:send(1,".$obj->userID.",\"".$obj->username."\");'>speichern</a>"
					 . "&nbsp;&nbsp;";
			 if ($type=='Admin') {
					echo "<a href='javascript:send(2,".$obj->userID.",\"".$obj->username."\");'>entfernen</a>";
			 }
			 if ($obj->username == $_SESSION['login_user']) {
				 echo '&nbsp;&nbsp<a><b>MYSELF</b></a>';
			 }
			 echo "</td></tr>"; 
			 //writeColumn2($obj->updatedAt,'width:130px');
			 //writeColumn2($obj->updatedBy,'width:50px');
			 //writeColumn2($obj->createdAt,'width:130px');
			 //writeColumn2($obj->createdBy,'width:50px');
			 echo '</tr>';
		}
		
		//echo '<input type = "submit" value = " Submit "/><br />';
		echo '</tbody>';
	}
	
  echo '</form>';	
	echo '</body>';
	
	if ($type=='Admin') {
		
	}
?>