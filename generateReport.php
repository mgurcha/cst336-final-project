<?php
session_start();
if (!isset($_SESSION['username'])) {  //checks whether the admin is logged in
    header("Location: index.php");
}

include 'database.php';
$conn = getDatabaseConnection();

if (isset($_POST["average"])){
    getAverage();
}
else{
    if(isset($_POST["2000"])){
        getMovies();
    }
    else if (isset($_POST["Ratings"])){
        getRatings();
    }
}


/*
Get the average number of artists from each genre.
*/

function getAverage(){
    global $conn;

    $sql = "SELECT COUNT(production_id), name
            FROM production_co natural join movies
            GROUP BY name order by count(production_id) desc";
    $stmt = $conn -> prepare($sql);
    $stmt->execute();
    $records = $stmt->fetchAll();
    return $records;
}

function getAverageProducers(){
    global $conn;
  $sql="select round(avg(amount)) from 
(select COUNT(production_id) as amount 
 FROM production_co natural join movies
 GROUP BY name) as T2;";

    $stmt = $conn -> prepare($sql);
    $stmt->execute();
    $records = $stmt->fetchAll();
    return $records;
}

function getAverageGenre(){
    global $conn;

    $sql = "select round(avg(amount)) from 
            (select count(movie_id) as amount, 
            movie from movies 
            natural join category 
            NATURAL JOIN movie_category group by movie) as T2";
    $stmt = $conn -> prepare($sql);
    $stmt->execute();
    $records = $stmt->fetchAll();
    return $records;
}

function getCategory(){
    global $conn;

    $sql = "select count(category_name), category_name from movies 
            natural join category 
            NATURAL JOIN movie_category 
            group by category_name order by count(category_name) desc";
    $stmt = $conn -> prepare($sql);
    $stmt->execute();
    $records = $stmt->fetchAll();
    return $records;
}

function getGenre(){
    global $conn;


    $sql = "select count(movie_id), movie from movies 
            natural join category 
            NATURAL JOIN movie_category 
            group by movie order by count(movie_id) desc;";
    $stmt = $conn -> prepare($sql);
    $stmt->execute();
    $records = $stmt->fetchAll();
    return $records;
}

/*
Gets Movies before 2000.
*/

function getMovies(){
    global $conn;

            
    $sql="SELECT *
            FROM movies
            NATURAL JOIN production_co
            WHERE year < 2010
            ORDER BY year desc";
            
    $stmt = $conn -> prepare($sql);
    $stmt->execute();
    $records = $stmt->fetchAll();
    return $records;
}

function getRatings(){
    global $conn;

    
    $sql="SELECT * FROM rating NATURAL JOIN movies NATURAL JOIN production_co order by movie";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $records = $stmt->fetchAll();
    return $records;
}

function getAvgRating(){
    global $conn;
   
    $sql="SELECT round(avg(rating),1) FROM rating;"; 
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $records = $stmt->fetchAll();
    return $records;
}

function displayRatings(){
    $records = getRatings();
    echo ' <table class="table table-striped table table-inverse">
    <thead align = "left">
      <tr>
      <th>Movie Name</th>
        <th>Rating</th>
        <th>Year</th>
      </tr>
    </thead>
    <tbody>';
    foreach($records as $record){
      echo '<tr>';
      echo  "<td>" .$record["movie"]. "<td>" . $record["rating"] . "<td>". $record["year"];
      echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}

function displayMovies(){
    $records = getMovies();
    echo ' <table class="table table-striped table table-inverse">
    <thead align = "left">
      <tr>
      <th>Movie Name</th>
        <th>Genre</th>
        <th>Year</th>
        <th>Production Company</th>
      </tr>
    </thead>
    <tbody>';
    foreach($records as $record){
      echo '<tr>';
      echo  "<td>" .$record["movie"]. "<td>" . $record["genre"] . "<td>" . $record["year"] . "<td>" . $record["name"];
      echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    
}
//Displays the Average:
function displayAverage(){
    $records = getAverage();
 echo ' <table class="table table-striped table table-inverse">
    <thead align = "left">
      <tr>
        <th>Movies Count</th>
        <th>Production Company</th>
      </tr>
    </thead>
    <tbody>';
    foreach($records as $record){
      echo '<tr>';
      echo  "<td>" . $record["COUNT(production_id)"] . "<td>" . $record["name"];
      echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}

function displayAvgRating(){
    $records = getAvgRating();
 echo ' <table class="table table-striped table table-inverse">
    <<thead align = "left">
      <tr>
        <th>Rounded Average Movie Rating:</th>
      </tr>
    </thead>
    <tbody>';
    foreach($records as $record){
      echo '<tr>';
      echo  "<td>" . $record["round(avg(rating),1)"];
      echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}

function displayAverageProducers(){
    $records = getAverageProducers();
 echo ' <table class="table table-striped table table-inverse">
    <thead align = "left">
      <tr>
        <th>Rounded Average of the count of movies for producers</th>
      </tr>
    </thead>
    <tbody>';
    foreach($records as $record){
      echo '<tr>';
      echo  "<td>" . $record["round(avg(amount))"];
      echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}


function displayGenre(){
    $records = getGenre();
 echo ' <table class="table table-striped table table-inverse">
    <thead align = "left">
      <tr>
        <th>Genre Count</th>
        <th>Movie</th>
      </tr>
    </thead>
    <tbody>';
    foreach($records as $record){
      echo '<tr>';
      echo  "<td>" . $record["count(movie_id)"] . "<td>" . $record["movie"];
      echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}
function displayAverageGenre(){
    $records = getAverageGenre();
 echo ' <table class="table table-striped table table-inverse">
    <thead align = "left">
      <tr>
        <th>Rounded Average of the count of different genres movies have</th>
      </tr>
    </thead>
    <tbody>';
    foreach($records as $record){
      echo '<tr>';
      echo  "<td>" . $record["round(avg(amount))"];
      echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}

function displayCategory(){
    $records = getCategory();
    echo '<br/> 
    <h2> NOTE: Same movie can be in multiple genres.</h2>';
 echo ' <table class="table table-striped table table-inverse">
    <thead align = "left">
      <tr>
        <th>Count of Movies</th>
        <th>Genre</th>
      </tr>
    </thead>
    <tbody>';
    foreach($records as $record){
      echo '<tr>';
      echo  "<td>" . $record["count(category_name)"] . "<td>" . $record["category_name"];
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
        <script text = "javascript" src = "js/util.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    </head>
    <div class="topnav" id="myTopnav">
  <a href="index.php">Home</a>
  <a href="user.php">Users</a>
  <a href="admin.php">Admins</a>
</div

    <body>
        <h1>Generate Reports:</h1>
        </div>
        <div class="btn-group">
            <button id="b_button" onclick="showAverage()">Generate Form: <br>
            Average Production Company & Count of production companies</button>
            <br>
            <button id="b_button" onclick="showMovies()">Generate Form: <br> Movies before 2010</button>
            <br>
            <button id="b_button" onclick = "showRatings()">Generate Form: <br> Movie Ratings & 
            Average Movie Rating</button>
            <br>
            <button id="b_button" onclick = "showGenre()">Generate Form: <br> 
            Count of Genres per movie & Average amount of genres for movies</button>
            <br>
            <button id="b_button" onclick = "showCategory()">Generate Form: <br> Count of Movies in each Genre</button>
            <br>
        </div>
        <div id="AvgProducers">
            <br>
            <?=displayAverageProducers()?>
        </div>
        <div id = "average" >
            <br>
            <h3>Count of movies per producer:</h2>
            <br>
            <?=displayAverage()?>
        </div>
        
        <div id = "Movies" >
              <br>
            <?=displayMovies()?>
        </div>
        <div id="AvgRating">
            <br>
            <?=displayAvgRating()?>
        </div>
        <div id = "Ratings" >
            <br>
            <h3>Ratings per Movie:</h2>
            <br>
            <?=displayRatings()?>
        </div>
        
        <div id = "category" >
            <br>
            <?=displayCategory()?>
        </div>
        <div id = "genreAvg" >
            <br>
            <?=displayAverageGenre()?>
        </div>
        <div id = "genre" >
            <br>
            <h3>The Genre's each movie has:</h2>
            <br>
            <?=displayGenre()?>
        </div>
    </body>
</html>