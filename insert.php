<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cerinfo";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT `Username`, `Password` FROM `logininfo` WHERE Username = '$username' AND Password = '$password'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    session_start();
	$_SESSION['Username'] = mysqli_fetch_assoc($result)['Username'];
	header('Location: index.php');
    }
else {
    echo ("<script>
    window.alert('Invalid Login');
    window.location.href='home.php';
    </script>");
}

?>

