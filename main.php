<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN" "http://www.w3.org/TR/REC-html40/strict.dtd">
<html>
<head>
	<meta charset="UTF-8">
	<title>Facebook Search</title>
	<style>
		table {
    		border-collapse: collapse;
		}
		table, td, th {
    		border: 2px solid lightgray;
		}
	</style>
	<script type="text/javascript">
		function run(){
			var optionSelected = document.getElementById("type");
			var hiddenL = document.getElementById("hiddenL");
			if(optionSelected.value !="place"){
				hiddenL.style.display = "none";
			}
			else{
				hiddenL.style.display = "";
			}
		}
		function clearUp(){
			var optionSelected = document.getElementById("type");
			var keywordtextfield = document.getElementById("keyword");
			var locationtextfield = document.getElementById("location");
			var distancetextfield = document.getElementById("distance");
			var element1 = document.getElementById("all");
			optionSelected.selectedIndex = 0;
			keywordtextfield.value="";
			locationtextfield.value="";
			distancetextfield.value="";
			element1.style.display="none";
			if(optionSelected.value !="place"){
				hiddenL.style.display = "none";
			}
			else{
				hiddenL.style.display = "";
			}
		}
		function showHideAlbums(){
			var element = document.getElementById("albumname");
			if (element != null) {
				if(element.style.display=="none"){
					element.style.display = "";
				}
				else{
					element.style.display = "none";
				}
			}			
		}
		function showHidePosts(){
			var element = document.getElementById("postname");
			if (element != null) {
				if(element.style.display=="none"){
					element.style.display = "";
					var element = document.getElementById("albumname");
					if (element != null) {
						if(element.style.display==""){
							element.style.display = "none";
						}
					}
				}
				else{
					element.style.display = "none";
				}
			}			
		}
		function showHideAlbumElement(mID){
			var element = document.getElementById("album".concat(mID))
			if(element.style.display=="none"){
				element.style.display = "";
			}
			else{
				element.style.display = "none";
			}
		}
	</script>
</head>
<body>
	<div id="outerbox">
		<div style="
			text-align: center;
			width: 800px;
			background-color:lightgray;
			border: 1px solid gray;
			margin-left:auto;
			margin-right:auto;
			display:;
		">
			<h2>Facebook Search</h2>
			<hr>
			<form action = "<?php $_PHP_SELF ?>" method="GET" id="form">
				<table style="margin-left:auto; margin-right:auto; text-align:left">
					<tr>
						<td>Keyword</td>
						<td>
							<input type="text" id="keyword" name="keyword" 
							value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : "" ?>" required>
						</td>
					</tr>
					<tr>
						<td>Type:</td>
						<td>
							<select id="type" name="selected" onchange="run()">
								<option value="user" 
									<?php 
										echo $_GET['selected']!="page" &&
										$_GET['selected']!="event" &&
										$_GET['selected']!="group" &&
										$_GET['selected']!="place" 
										? "selected" : "" 
									?>
									>Users
								</option>
								<option value="page" <?php echo $_GET['selected']=="page" ? "selected" : "" ?>>Pages</option>
								<option value="event" <?php echo $_GET['selected']=="event" ? "selected" : "" ?>>Events</option>
								<option value="group" <?php echo $_GET['selected']=="group" ? "selected" : "" ?>>Groups</option>
								<option value="place" <?php echo $_GET['selected']=="place" ? "selected" : "" ?>>Places</option>
							</select>
						</td>
					</tr>
					<tr id="hiddenL" style="display:<?php echo $_GET['selected']=="place" ? "" : "none" ?>">
						<td>Location</td>
						<td><input type="text" id="location" name="location" 
							value="<?php echo isset($_GET['location']) ? $_GET['location'] : '' ?>">
						</td>
						<td>Distance(meters)</td>
						<td><input type="text" id="distance" name="distance"
							value="<?php echo isset($_GET['distance']) ? $_GET['distance'] : '' ?>"></td>
					</tr>
					<tr>
						<td>
							<input type="submit" name="Search" value="Search">
							<input type="button" value="Clear" onclick="clearUp()">
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
	<?php
		require_once __DIR__ . '/php-graph-sdk-5.0.0/src/Facebook/autoload.php';
		date_default_timezone_set("America/Los_Angeles");
		echo '<div id="all">';
		if ($_SERVER["REQUEST_METHOD"] == "GET") {
			$fb = new Facebook\Facebook([
  				'app_id' => '1845517239059399',
  				'app_secret' => '051defe8fd89cd73081215ad5be3a94c',
  				'default_access_token' => 'EAAaOfPd2A8cBAGib6KzrQbX4sEZAngl8WnrFNwsv86nIjZCzsNr0MmvHpNlSrhXsxbZB7Kw6nzPZCsclLu3XsuNWLvZAEWIrY8IhC6SSRpZCASigLMoNR59JzWyuDsVJfP0pXVhcDbHrAT09EIik5lCHwji2BtI5sZD',
  				'default_graph_version' => 'v2.5',
			]);
			if(isset($_GET['id'])){
				$response = $fb->get('/'.$_GET['id'].'?fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){name, picture}},posts.limit(5)');
				//echo '/'.$_GET['id'].'?fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){name, picture}},posts.limit(5)';
				$users = $response->getGraphNode()->AsArray();
				//print_r($users);
				$albumCount = 1;
				echo '<div style="width:1000px;margin-left:auto;margin-right:auto">';
				if (is_array($users['albums']) || is_object($users['albums'])){
					echo '<table style="width:1000px;margin-top:30px"><tr style="text-align:center;background-color:gray"><td><a href="javascript:showHideAlbums()">Albums</a></td></tr></table>';
					echo '<table style="margin-top:30px;width:1000px;display:none" id="albumname">';
					foreach($users['albums'] as $album){
						echo '<tr style="text-align:left;"><td><a href="javascript:showHideAlbumElement('.$albumCount.')">'.$album['name'].'</a></td></tr>';
						echo '<tr style="text-align:left;display:none" id="album'.$albumCount.'"><td>';
						foreach($album['photos'] as $photo){
							$id = $photo['id'];
							$link = "http://graph.facebook.com/{$id}/picture?";
							echo '<a href="'.$link.'"><img src="'.$photo['picture'].'" width=100 height=100 style="float:left"></a>';
						}
						echo '</td></tr>';
						$albumCount = $albumCount + 1;
					}
				}
				else{
					echo '<table style="width:1000px;margin-top:30px"><tr style="text-align:center;width:1000px"><td>No Albums has been found</td></tr>';
				}
				echo '</table>';
				
				if (is_array($users['posts']) || is_object($users['posts'])){
					$valid_count = 0;
					foreach($users['posts'] as $post){
						if($post['message']!=null && $post['message']!=""){
							$valid_count = $valid_count + 1;
						}
					}
					if($valid_count>0){
						echo '<table style="width:1000px;margin-top:30px"><tr style="text-align:center;background-color:gray;width:1000px"><td><a href="javascript:showHidePosts()">Posts</a></td></tr></table>';
						echo '<table style="margin-top:30px;width:1000px;display:none" id="postname">';
						echo '<tr style="text-align:left;background-color:lightgray"><th>Messages</th></tr>';
						foreach($users['posts'] as $post){
							if($post['message']!=null && $post['message']!=""){
								echo '<tr style="text-align:left"><td>'.$post['message'].'</td></tr>';
							}
						}
					}
					else{
						echo '<table style="width:1000px;margin-top:30px"><tr style="text-align:center;width:1000px"><td>No Posts has been found</td></tr>';
					}
				}
				else{
					echo '<table style="width:1000px;margin-top:30px"><tr style="text-align:center;width:1000px"><td>No Posts has been found</td></tr>';
				}
				echo '</table></div>';
			}
			else{
				if($_GET['selected']=="user" || $_GET['selected']=="page" || $_GET['selected']=="group"){
					$response = $fb->get('/search?q='.$_GET['keyword'].'&type='.$_GET['selected'].'&fields=id,name,picture.width(700).height(700)');
					$users = $response->getGraphEdge()->AsArray();
					$count = 0;
					foreach($users as $user){
						$count = $count + 1;
					}
					echo '<div style="width:1000px;margin-left:auto;margin-right:auto">';
					echo '<table style="margin-top:30px;width:1000px">';
					if($count > 0){
						echo '<tr style="text-align:left;background-color:lightgray"><th>Profile Photo</th><th>Name</th><th>Details</th></tr>';
						foreach($users as $user){
							echo '<tr><td>';
							echo '<a href="'.$user['picture']['url'].'"><img src="'.$user['picture']['url'].'" width=30 height=30></a></td>';
							echo '<td>'.$user['name'].'</td>';
							echo '<td><a href="Search.php?id='.$user['id'].'&keyword='.$_GET['keyword'].'&selected='.$_GET['selected'].'&location='.$_GET['location'].'&distance='.$_GET['distance'].'">Details</a>';
							echo '</td></tr>';
						}
					}
					else{
						echo '<tr style="text-align:center;background-color:white"><td>No Records have been found.</td></tr>';
					}
					echo '</table></div>';
				}
				else if($_GET['selected']=="event"){
					$response = $fb->get('/search?q='.$_GET['keyword'].'&type='.$_GET['selected'].'&fields=id,name,picture.width(700).height(700),place');
					$users = $response->getGraphEdge()->AsArray();
					$count = 0;
					foreach($users as $user){
						$count = $count + 1;
					}
					echo '<div style="width:1000px;margin-left:auto;margin-right:auto">';
					echo '<table style="margin-top:30px;width:1000px">';
					if($count > 0){
						echo '<tr style="text-align:left;background-color:lightgray"><th>Profile Photo</th><th>Name</th><th>Place</th></tr>';
						foreach($users as $user){
							echo '<tr><td>';
							echo '<a href="'.$user['picture']['url'].'"><img src="'.$user['picture']['url'].'" width=30 height=30></a></td>';
							echo '<td>'.$user['name'].'</td>';
							echo '<td>'.$user['place']['name'];
							echo '</td></tr>';
						}
					}
					else{
						echo '<tr style="text-align:center;background-color:white"><td>No Records have been found.</td></tr>';
					}
					echo '</table></div>';
				}
				else if($_GET['selected']=="place"){
					// using cURL get the lat and long values via Google Geocoding
					$yourkey="AIzaSyBUesOtuSWPv6h5xZhZ4ddhpAVtlVPdiPE";
					$address=urlencode($_GET['location']);
					$url='https://maps.googleapis.com/maps/api/geocode/json?address='.$address.'&key='.$yourkey;
					$curl = curl_init();
					curl_setopt($curl, CURLOPT_URL, $url);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
					curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

					$success = curl_exec($curl);
					if($success || ($_GET['location']=="" && $_GET['distance']=="")){
						{
							// get long and lat values
							$json = json_decode($success);
							$lat = $json->results[0]->geometry->location->lat;
							$long = $json->results[0]->geometry->location->lng;
						}
						if(($_GET['location']=="" && $_GET['distance']=="")|| (is_float($lat)&&is_float($long)&&preg_match('/^[+\-]?\d+(\.\d+)?$/',($_GET['distance'])))){
							if($_GET['location']=="" && $_GET['distance']==""){
								$response = $fb->get('search?q='.$_GET['keyword'].'&type=place&center='."&distance=".$_GET['distance']."&fields=id,name,picture.width(700).height(700)");
							}
							else{
								$response = $fb->get('search?q='.$_GET['keyword'].'&type=place&center='.$lat.','.$long."&distance=".$_GET['distance']."&fields=id,name,picture.width(700).height(700)");
							}
							$users = $response->getGraphEdge()->AsArray();
							$count = 0;
							foreach($users as $user){
								$count = $count + 1;
							}
							echo '<div style="width:1000px;margin-left:auto;margin-right:auto">';
							echo '<table style="margin-top:30px;width:1000px" >';
							if($count > 0){
								echo '<tr style="text-align:left;background-color:lightgray"><th>Profile Photo</th><th>Name</th><th>Details</th></tr>';
								foreach($users as $user){
									echo '<tr><td>';
									echo '<a href="'.$user['picture']['url'].'"><img src="'.$user['picture']['url'].'" width=30 height=30></a></td>';
									echo '<td>'.$user['name'].'</td>';
									echo '<td><a href="Search.php?id='.$user['id'].'&keyword='.$_GET['keyword'].'&selected='.$_GET['selected'].'&location='.$_GET['location'].'&distance='.$_GET['distance'].'">Details</a>';
									echo '</td></tr>';
								}
							}
							else{
								echo '<tr style="text-align:center;background-color:white"><td>No Records have been found.</td></tr>';
							}
							echo '</table></div>';
						}
						else{
							echo '<div style="width:1000px;margin-left:auto;margin-right:auto">';
							echo '<table style="width:1000px;margin-top:30px"><tr style="text-align:center;width:1000px"><td>Error in inputs!</td></tr></table></div>';
						}
					}
					else{
						echo '<div style="width:1000px;margin-left:auto;margin-right:auto">';
						echo '<table style="width:1000px;margin-top:30px"><tr style="text-align:center;width:1000px"><td>Error!</td></tr></table></div>';
					}
				}
			}
		}
		echo '</div>';
	?>
</body>
</html>























