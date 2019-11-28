<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
// Check if the user is logged in, if not then redirect him to login page

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>s3557584</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css"/>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="javascript/jquery.csv.min.js"></script> 
  <script type="text/javascript" src="https://www.google.com/jsapi"></script>
  <script>
$(document).ready(function() {
	
	// fade in .navbar
	$(function () {
		$(window).scroll(function () {
            // set distance user needs to scroll before we fadeIn navbar
			if ($(this).scrollTop() < 10) {
				$('.navbar').fadeIn();
			} else {
				$('.navbar').fadeOut();
			}
		});

	
	});
 $("#getMessage").on("click", function() {
  var valueSearchBox = $('#getText').val()
  if (valueSearchBox === "") {
   return;
  }
  select();
 });
 //--------------------------------------------------SEARCH BY CITY-----------------------------------------

 function select() {
  var valueDropdown = "Melbourne"
  var valueSearchBox = $('#getText').val()
  var searchCity = "&q=" + valueSearchBox;
  var settings = {
   "async": true,
   "crossDomain": true,
   "url": "https://developers.zomato.com/api/v2.1/search?entity_id=" + valueDropdown + "&entity_type=city" + searchCity + "&count=10",
   "method": "GET",
   "headers": {
    "user-key": "39dc8aec87ae1aa4d4f9cfdcbea85961",
    'Content-Type': 'application/x-www-form-urlencoded'
   }
  }

  $.getJSON(settings, function(data) {

   data = data.restaurants;
   var html = "";
     html += "<div class='main'>";
	 html += "<form action='favourite.php' method='post'>";
	 html += "<input type='submit' class='btn btn-danger' value='Save' name='Save'>";
   $.each(data, function(index, value) {

    var x = data[index];
    $.each(x, function(index, value) {
     var location = x.restaurant.location;
     var userRating = x.restaurant.user_rating;
	 html += "<div class='data img-rounded'>";
     html += "<div class='rating'>";
	 html += "<div class='pretty p-icon p-rotate'>";
     html += "<input type='checkbox' name='resName[]' value='"+value.id+"'>";
     html += "<div class='state p-success'>";
     html += "<i class='icon mdi mdi-check'></i>";
     html += "<label>Add Favourite</label>";
     html += "</div>"
     html += "</div>"
	 //html += "<input type='checkbox' name='resName[]' value='"+value.id+"'>";
     html += "<span title='" + userRating.rating_text + "'><p style='color:white;background-color:#" + userRating.rating_color + ";border-radius:4px;border:none;padding:2px 10px 2px 10px;text-align: center;text-decoration:none;display:inline-block;font-size:16px;float:right;'><strong>" + userRating.aggregate_rating + "</strong></p></span><br>";
     html += "  <strong class='text-info'>" + userRating.votes + " votes</strong>";
     html += "</div>";
     html += "<img class='resimg img-rounded' src=" + value.thumb + " alt='Restaurant Image' height='185' width='185'>";
     html += "<a href=" + value.url + " target='_blank' class='action_link'><h2 style='color:red;'><strong>" + value.name + "</strong></h2></a>";
     html += "  <strong class='text-primary'>" + location.locality + "</strong><br>";
     html += "  <h6 style='color:grey;'><strong>" + location.address + "</strong></h6><hr>";
     html += "  <strong>CUISINES</strong>: " + value.cuisines + "<br>";
     html += "  <strong>COST FOR TWO</strong>: " + value.currency + value.average_cost_for_two + "<br>";
	 html += "<h2>Location:</h2>";
	 html += "<a href='https://maps.google.com/?q=term"+location.address+"'>";
	 html += "<img class='border-round' src='https://maps.googleapis.com/maps/api/staticmap?center="+location.latitude+","+location.longitude+"&zoom=16&size=420x200&markers=color:red%7C"+location.latitude+","+location.longitude+"&key=AIzaSyB_VxAatlhtl3iIX_O9qM7qmmijSxWPoA8'>";
     html += "</a>";
	 html += "</div><br>";
	 html += "<hr>";
    });
   });
	html += "<input type='submit' class='btn btn-danger' value='Save' name='Save'>";
	html += "</form>";
	html += "</div>";
   $(".message").html(html);
  });

 }
 //--------------------------------------------------------------------------------------------------------
 $("#select_id").change(function() {
  select();
 });
});
</script>
<link rel="stylesheet" type="text/css" href="css/CSS.css">

<script type='text/javascript'>


google.charts.load('current', {
  packages: ['corechart']
}).then(function () {
  // declare data variable
  var arrayData;
  // get csv data
  $.get('resource/rating.csv', function(csvString) {
    // get csv data success, convert to an array, draw chart
    arrayData = $.csv.toArrays(csvString, {onParseValue: $.csv.hooks.castToScalar});
    drawChart(arrayData);
  }).fail(function () {
    // get csv data failed, draw chart with hard-coded data, for example purposes
    arrayData = [
      ['Restaurant Name','Rating'],
      ['Humble Rays',4.8],
      ['Dodee Paidang',4.6],
      ['Shanklin Cafe',4.9],
      ['KisumÃ©',4.6],
      ['Davids Hot Pot',4.5]
    ];
    drawChart(arrayData);
  });
});

// draw chart function 
function drawChart(arrayData) {
//   arrayData = arrayData.map(function (row) {
//     return [new String(row[0]),row[1]];
//   });

  // create google data table, chart, and options
  var data = google.visualization.arrayToDataTable(arrayData);
  var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
  var options = {
    title: "Melbourne CBD - Restaurant Rating"
  };

  // draw chart
  chart.draw(data, options);
}
</script>
<style>
.chart {
    max-width: 100%;
    max-height: 100%;
}
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar navbar-dark bg-success">
		<h6 class="navbar-brand">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b><br> Welcome to our site.<br></h6><br>
        <a href="#" class="navbar-brand"></a>
		<img class="logo" src='https://img.pngio.com/logo-go-food-vector-cdr-png-hd-go-food-png-1600_1200.jpg' />
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse4">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarCollapse4">
            <div class="navbar-nav">
                <a href="favourite.php" class="nav-item nav-link active">Favourite</a>
            </div>
            <div class="form-inline ml-auto">
			<label class="navbar-brand">Enter any food restaurant keyword:</label><br>
                <input id="getText" type="text" class="form-control mr-sm-2" placeholder="Search">
                <button id = "getMessage" class="btn btn-outline-light" style="margin-right: 20px;">Search</button>
            </div>
        </div>
		<a href="logout.php" class="btn btn-danger">Click Here to Sign Out</a>
    </nav>
	<div class="chart_container" style="padding-top:135px;">
		<div class="chart" id="chart_div"></div>
	</div>
    <div class="container" style="padding-top: 20px;">
		<div>
			<div class = "message col-md-12"></div>
		</div>
	</div>
</body>
</html>