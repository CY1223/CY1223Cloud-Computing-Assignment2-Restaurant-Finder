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
<?php
   $username=$_SESSION["username"];
   $fileUrl= ('gs://cloud-assignment-2-2019/'.$username.'.txt');
   $aRestaurants = array();
		if(isset($_POST['Save'])){
				  if(!file_exists($fileUrl)){
					  $aRestaurants = $_POST['resName'];
					  if(empty($aRestaurants)){
							echo("You didn't select any Restaurants.");
					  }else{
						    $handle = fopen($fileUrl,'w');
							foreach($aRestaurants as $resname){
							fwrite($handle,$resname."\n");
						}
					  }
				  }else{
					  $fromFile = explode("\n", file_get_contents($fileUrl));
					  $fromPost = $_POST['resName'];
					  if(empty($fromPost)){
						  foreach($fromFile as $file){
							  array_push($aRestaurants,$file);
						  }
						  echo '<script language="javascript">';
						  echo 'alert("You did not add any restaurants")';
						  echo '</script>';
					  }else{
						  $handle = fopen($fileUrl,'w');
						  foreach($fromPost as $post){
							  array_push($aRestaurants,$post);
						  }
						  foreach($fromFile as $file){
							  array_push($aRestaurants,$file);
						  }
						  foreach($aRestaurants as $resname){
							fwrite($handle,$resname."\n");
						}
						  
					  }
					  fclose($handle); 
				  }
				  
		}else{
			$aRestaurants = explode("\n", file_get_contents($fileUrl));
		}
		
?> 
<!DOCTYPE html>
<html lang="en">
<head>
  <title>s3557584</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_VxAatlhtl3iIX_O9qM7qmmijSxWPoA8&callback"></script>
	<script type="text/javascript" src="javascript/jquery.googlemap.js"></script>
  <script>
	 $(document).ready(function() {
		 var resID = <?php echo json_encode($aRestaurants); ?>;
		 console.log(resID);
		 $("#map").googleMap();
		 var ID = resID.length;
		 var array = [];
		 for(var i=0; i<ID; i++){
			 array=resID[i];
		 var settings = {
   "async": true,
   "crossDomain": true,
   "url": "https://developers.zomato.com/api/v2.1/restaurant?res_id=" + array,
   "method": "GET",
   "headers": {
    "user-key": "39dc8aec87ae1aa4d4f9cfdcbea85961",
    'Content-Type': 'application/x-www-form-urlencoded'
   }
  }
		 $.getJSON(settings, function(data) {
			 datalocation = data.location;
			 dataurl= data.url;
			 dataresname= data.name
			 $.each(data, function(index, value) {
				  $("#map").addMarker({
					coords: [datalocation.latitude, datalocation.longitude],
					url: dataurl,
					title: dataresname
				});
			 });
		 });
	}
});

  </script>
  <link rel="stylesheet" type="text/css" href="css/CSS.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar navbar-dark bg-success">
<h6 class="navbar-brand">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b><br> Here are your added favourites.<br></h6><br>
		<img class="logo" src='https://img.pngio.com/logo-go-food-vector-cdr-png-hd-go-food-png-1600_1200.jpg' />
<div class="collapse navbar-collapse" id="navbarCollapse4">
            <div class="navbar-nav">
                <a href="index.php" class="nav-item nav-link active">Home</a>
            </div>
        </div>
		<a href="logout.php" class="btn btn-danger">Logout</a>
    </nav>

<div class="container" style="margin-top: 200px;">
  <div class="row">
    <div class="col-sm-8">
	  <div id="map" class="centered" style="width: 1100px; height: 500px;">
  </div>
    </div>
  </div>
</div>

</body>
</html>
