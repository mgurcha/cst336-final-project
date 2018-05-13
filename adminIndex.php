<?php
session_start();
include 'database.php';
$conn = getDatabaseConnection();

if (!isset($_SESSION['username'])) {  //checks whether the admin is logged in
    header("Location: index.php");
}

function displayInfo(){
    global $conn;
    $sql = "SELECT *
            FROM movies";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo ' <table class="table table-striped table table-inverse">
    <thead align = "left">
      <tr>
        <th>Movie</th>
        <th>Year</th>
        <th>Genre</th>
        <th>Image URL</th>
        <th>Update</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody>';
    foreach($records as $record){
      echo '<tr>';
      echo  "<td>" . $record["movie"] . "<td>" . $record["year"] . "<td>" . $record["genre"] . "<td>" . $record["img"] . "<td>" . "[<a href='updateInfo.php?movie_id=".$record["movie_id"]."&production_id=".$record["production_id"] . "'> Update </a>]"
       . "<td>" . "[<a onclick='return confirmDelete()'href='deleteInfo.php?movie_id=".$record["movie_id"]."'> Delete </a>]";
      echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}


?>



<!DOCTYPE html>
<html>
    <title>
        
    </title>
    <head>
        <link  href="css/styles.css" rel="stylesheet" type="text/css" />
        <script>
            
            function confirmDelete(){
                
                return confirm("Are you sure you want to delete this Movie?");
            }
            
            
        </script>
    </head>
    <div class="topnav" id="myTopnav">
  <a href="index.php">Home</a>
  <a href="user.php">Users</a>
  <a href="admin.php">Admins</a>
  <a href="logout.php"style="float:right !important">Logout</a>
  
    
</div>
<div class="btn-group btn-group-lg">
  <button type="button" class="btn btn-primary" onclick="location.href='insertInfo.php'">Insert Record</button>
  <button type="button" class="btn btn-primary" onclick="location.href='generateReport.php'">Generate Reports</button>
</div>
    <body>
        <?=displayInfo()?>
    </body>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
</html>