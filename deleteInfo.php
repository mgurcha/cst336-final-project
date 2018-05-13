<?php
    include 'database.php';
    $conn = getDatabaseConnection();
    
    $sql = "DELETE FROM movies
            WHERE movie_id =" . $_GET['movie_id'];
            
            
    $stmt = $conn->prepare($sql);
    $stmt -> execute();
    header("Location: adminIndex.php");
?>