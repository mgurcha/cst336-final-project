<?php
session_start();

include 'database.php';
$conn = getDatabaseConnection();

function getCategoriesHTML() {
    global $conn;
    $categoriesHTML = "";  // User can opt to not select a category 
    $sql = "SELECT category_name FROM category"; 
    
    $statement = $conn->prepare($sql); 
    $statement->execute(); 
    $records = $statement->fetchAll(PDO::FETCH_ASSOC); 
    
    foreach ($records as $record) {
        $category = $record['category_name']; 
        $categoriesHTML .= "<option value='$category'>$category</option>"; 
    }
    
    return $categoriesHTML; 
}
function displayTable(){
    $param = $_POST["sort"];
    $param2 = $_POST["list"];
    
    if (isset($_POST["sort"]) && !empty($query)){
        $param = $_POST["sort"];
    }
    if (isset($_POST["list"]) && !empty($query)){
        $param2 = $_POST["list"];
    }
    if (isset($_POST["query"])  && !empty($query)){
         $query=$_POST["query"];
    }
    if(isset($_POST["category"])  && !empty($category)){
        $category = $_POST["category"];
    }
    if(isset($_POST['submitted'])){
        showAllRecords($param, $query, $param2);
    }
    if(isset($_POST['submitted-category'])){
        showCategory($category);
    }
}


function showCategory($category){
    global $conn;
    
    $sql = "select * from movies natural join 
    category natural join movie_category 
    natural join production_co 
    natural join rating where category_name 
    LIKE '%" .$_POST["category"]. "%'" ;
    
    if($_POST['ordering'] == 'desc'){
        $sql .= " ORDER BY movie DESC;";
    }
    else{
        $sql .= " ORDER BY movie ASC;";
    }
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    $records = $stmt->fetchAll();
    echo '<br>';
    
    
    echo ' <table class="table table-striped">
    <thead align = "left">
      <tr>
        <th>Movie Name</th>
        <th>Genre</th>
        <th>Year</th>
        <th>Rating</th>
        <th>Production Company</th>
      </tr>
    </thead>
    <tbody>';
    foreach($records as $record){
    echo '<tr>';
      echo  "<td>" . $record["movie"] . "<td>" . $record["genre"] . "<td>" . $record["year"] . "<td>" . $record["rating"]. "<td>" . $record["name"];
      echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}


function showAllRecords($param, $query, $param2){
    global $conn;
    
    if($param == "Sort By Movie Name"){
        $param = "movie";
    }
    else {
        if($param == "Sort By Genre"){
            $param = "genre";
        }
        else if($param == "Sort By Rating"){
            $param = "rating";
        }
        else if($param =="Sort By Production Company"){
            $param = "name";
        }
        else if($param =="Sort By Year"){
             $param = "year";
        }
        else{
            $param = " ";
        }
    }
    
    if($param2 == "Rating"){
        $param2 = " AND rating.rating > 8.0";
    }
    else{
        if($param2 =="RatingLess"){
             $param2 = " AND rating.rating < 8.0";
        }
        else if($param2 =="MovieLess"){
             $param2 = " AND movie.year < 2000";
        }
        else if($param2 =="MovieYear"){
             $param2 = " AND movie.year > 2000";
        }
        else{
            $param2 = " ";
        }
    }

    $sql="SELECT *
            FROM movies
            LEFT JOIN rating ON movies.movie_id=rating.movie_id 
            LEFT JOIN production_co on production_co.production_id=movies.production_id
            WHERE (LOWER(movie) LIKE LOWER('%" .$_POST["query"]. "%') " . $param2 . ")
             OR name LIKE LOWER('%" .$_POST["query"]. "%')
            OR genre LIKE LOWER('%" .$_POST["query"]. "%') " ;
   
    if($_POST['ordering'] == 'desc'){
        $sql .= " ORDER BY " . $param . " DESC;";
    }
    else if($_POST['ordering'] == 'asc'){
        $sql .= " ORDER BY " . $param ." ASC;";
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $records = $stmt->fetchAll();
    
        
        echo ' <table class="table table-striped">
        <thead align = "left">
          <tr>
            <th>Movie Name</th>
            <th>Genre</th>
            <th>Year</th>
            <th>Rating</th>
            <th>Production Company</th>
          </tr>
        </thead>
        <tbody>';
        foreach($records as $record){
        echo '<tr>';
          echo  "<td>" . $record["movie"] . "<td>" . $record["genre"] . "<td>" . $record["year"] . "<td>" . $record["rating"]. "<td>" . $record["name"];
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
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    </head>
    <div class="topnav" id="myTopnav">
      <a href="index.php">Home</a>
      <a href="user.php">Users</a>
      <a href="admin.php">Admins</a>
</div>

    <body>
    <form method = "post">
        <div id="layoutDiv">
        <label for="pName">Search For: </label>
        <input type="text" name="query" placeholder="Name" id="pName">
       <select id = "dropdown" name = "sort" >
            <option>- Sort By -</option>
            <option name = "sort" value = "Sort By Movie Name">Sort By Movie Name</option>
            <option name = "sort" value = "Sort By Genre">Sort By Genre</option>
            <option name = "sort" value = "Sort By Year">Sort By Year</option>
            <option name = "sort" value = "Sort By Rating">Sort By Rating</option>
            <option name = "sort" value = "Sort By Production Company">Sort By Production Company</option>
        </select><br />
        
        <select id = "dropdown" name = "list" >
            <option>- List Only Where -</option>
            <option name = "list" value = "Rating">Rating is greater than 8</option>
            <option name = "list" value = "RatingLess">Rating is less than 8</option>
            <option name = "list" value = "MovieLess">Year released is before 2000</option>
            <option name = "list" value = "MovieYear">Year released is after 2000</option>
        </select><br />
 
        <input type= "radio" id = "horizontal" name = "ordering" value = "asc">
        <label for  = "horizontal" ></label><label for = "horizontal">Ascending</label>
        <input type= "radio" id = "vertical" name = "ordering" value = "desc">
        <label for  = "vertical" ></label><label for = "vertical">Descending</label><br />
         <input type="submit"  value="Search!" name="submitted"><br />
        </div>
        
        <div id="layoutDiv2">
            <select id = "dropdown" select  name="category">
            <option>- Select A Genre -</option>
            <?php echo getCategoriesHTML(); ?>
        </select><br />
        <br><input type="submit"  value="List!" name="submitted-category"><br />
        </div>
        
    </form>
    <br /> <h2 id='selection'> Type a keyword and select a category to filter, or select a genre. <br /><h2 />

    <br>
    <div id = "userlist">
        <?=displayTable()?>
    </div>
    
    </body>
</html>