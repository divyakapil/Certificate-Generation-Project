<?php 
  
  session_start();
  if(isset($_SESSION['Username'])) {
        echo 'Welcome '.$_SESSION['Username'];
        echo '<a  href="index.php">report</a>';
      } else {
        echo '<a  href="home.php">report</a>';
      }
      
?>

<?php
		$conn=mysqli_connect("localhost","root","","cerinfo");
		if($conn-> connect_error)
		{
			die("connection failed:".$conn-> connect_error);
		}
?>

<!DOCTYPE html>
<html>
<head>
	<title>WELCOME USER!!!</title>
</head>
        
<body>
	<table align="center" border="1px" style="width:600px; line-height:30px;">
		<tr>
            <th><h1>User record</h1></th>
        </tr>
        <t><h2>
			<th>Username</th>
			<th>Event name</th>
			<th>Certi type</th>
			<th>Count</th>
            	<th>Date</th></h2>
		</t>
        <?php
        
       $sql="SELECT * FROM `report`";
		$result=$conn-> query($sql);
		if($result-> num_rows > 0)
		{
			while($row= $result-> fetch_assoc()){
				echo "<tr><td>".$row["username"] ."</td><td>".
                    $row["event name"] ."</td><td>".
                      $row["certi type"] ."</td><td>". 
                      $row["count"] ."</td><td>".
                      $row["date"] ."</td>";
        }
        
        ?>
       
        
        <?php
      $conn-> close();  }
        ?>
    </table>
    </body>
</html>
		