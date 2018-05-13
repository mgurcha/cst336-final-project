function randomize(){
    //randomize index page:
    //get info for randomize:
    var url = "ajaxInfo.php";
    var type = "GET";
    var datatype = "html";
    
    var ajax;
    ajax = new XMLHttpRequest();
    ajax.open("GET", url, true);
    ajax.send();
    ajax.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
        // alert(ajax.responseText);  //displays value retrieved from PHP program
        $("#frontPageBody").empty();
         var data = JSON.parse(ajax.responseText);
         var div = document.getElementById("frontPageBody");
         var div2 = document.getElementById("images");
         div.innerHTML += "Title: " + data["movie"] + "<br>";
         div.innerHTML += "Year: " + data["year"] + "<br>";
         div.innerHTML += "Genre: "  + data["genre"] + "<br>" + "<br>";
         var img = data["img"];
         div2.innerHTML = "<img id=\"image\" src=\"" + img + "\">";
        }
     }
}



function showAverage(){
    console.log("IN THE FUNCTION.");
    $('#average').toggle('show');
    $('#Movies').hide();
    $('#Ratings').hide();
    $('#genre').hide();
    $('#category').hide();
     $('#genreAvg').hide();
     $('#AvgProducers').show();
     $('#AvgRating').hide();
}

function showGenre(){
    $('#average').hide();
    $('#Movies').hide();
    $('#Ratings').hide();
    $('#genre').show();
    $('#genreAvg').show();
    $('#category').hide();
    $('#AvgProducers').hide();
    $('#AvgRating').hide();
}

function showMovies(){
    $('#average').hide();
    $('#Movies').show();
    $('#Ratings').hide();
    $('#genre').hide();   
    $('#category').hide();
    $('#genreAvg').hide();
    $('#AvgProducers').hide();
    $('#AvgRating').hide();
}
function showRatings(){
    $('#average').hide();
    $('#Movies').hide();
    $('#Ratings').show();
    $('#genre').hide();
    $('#category').hide();
    $('#genreAvg').hide();
    $('#AvgProducers').hide();
    $('#AvgRating').show();
}

function showCategory(){
    $('#average').hide();
    $('#Movies').hide();
    $('#Ratings').hide();
    $('#genre').hide();
    $('#category').show();
    $('#genreAvg').hide();
    $('#AvgProducers').hide();
    $('#AvgRating').hide();
    
}