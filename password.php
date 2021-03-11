<head>
  <meta charset="utf-8">
  <link rel= "stylesheet" type="text/css" href="ping.css">
</head>
<?php
include 'util/global.php';
include 'util/db.php';
include 'tables/user.php';
	
global $con;
	
$con = connectDB($domainNameGlobal,$dbuserGlobal,$dbpasswordGlobal,$schemaGlobal);

include 'util/session.php';	


if(isset($_GET['user'])) {
		
		echo '<a>User:<b> '.$_GET['user'].'</b></a><br>';
		
		if (isset($_POST['passwort1']) && isset($_POST['passwort2'])) {
			if ($_POST['passwort1'] == $_POST['passwort2']) {
				$query = 'UPDATE user '.
				         '  SET password="'.password_hash($_POST['passwort1'],PASSWORD_BCRYPT ).'" '.
								 ' WHERE username="'.$_GET['user'].'" ';
				mysqli_query($con,$query);
				if (mysqli_affected_rows($con)==1) {
					echo '<script LANGUAGE="JavaScript">'.
					     'setTimeout("self.close();", 1);'.
					     '</script>';
				} else {
					echo '<b>Fehler!</b>';
				}
				
			} else {
				echo '<b>Passwörter nicht identisch!</b>';
			}
		} else {
			echo '<br>';
		}
		
		echo "<form action='password.php?user=".$_GET['user']."' method='post'>";

	/* Neuer 	Eintrag */
		echo "<tr>" 
			 . "<td>Passwort<br><input name='passwort1' type='password' style='width: 200px; padding:0;'></td>"
			 . "<td><br><br>erneut Passwort<br><input name='passwort2' type='password' style='width: 200px; padding:0;'></td>"	
			 . "</tr>";	
		
		echo '<br><br><input type="submit" value="Speichern">';
		
		echo '</form>';	
		
		//echo 'A:'.$urlName;
	}
else {
	echo 'nicht möglich!';
}
	
?>