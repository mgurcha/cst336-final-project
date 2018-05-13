<?php
session_start();
include 'database.php';
if (isset($_SESSION['username'])) {  //checks whether the admin is logged in
    header("Location: adminIndex.php");
}

$conn = getDatabaseConnection();


if(isset($_POST["username"])){
    if(isset($_POST["password"])){
        verifyInput();
    }
}

function verifyInput(){
    $username = $_POST["username"];
    $password = $_POST["password"];
    $hashedPass = sha1($password);
    global $conn;
   
    
    $sql = "SELECT *
            FROM admins
            WHERE username = :username
            AND password = :password";
    $np = array();
    $np[':username'] = $username;
    $np[':password'] = $hashedPass;
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($np);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (empty($record)) {
        echo "Wrong Username or password";
     } else {
               $_SESSION['username'] = $record['username'];
               header("Location: adminIndex.php"); //redirecting to adminIndex.php
            }
}

?>

<!DOCTYPE html>
<html>
    <title>
        
    </title>
    <head>
        <link  href="css/styles.css" rel="stylesheet" type="text/css" />
    </head>
    <div class="topnav" id="myTopnav">
  <a href="index.php">Home</a>
  <a href="user.php">Users</a>
  <a href="admin.php">Admins</a>
</div

    <body>
        <h2>Credentials required before submitting form.</h2>
        <p>You can log in using usernames <strong>user_1</strong> or <strong>user_2</strong>. The password is <strong>s3cr3t</strong>.</p>
        
    <div id = "pass">
        <form method = "post">
            Username:
            <input type = "text" name = "username"  required/>
            <br>
            <br>
            Password:
            <input type = "password" name  = "password" required />
            <br>
            <br>
            <input type  = "submit" name  = "submit" value="Login"/>
        </form>
    </div>

    </body>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
</html>