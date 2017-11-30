<?php
	require_once __DIR__ . '/php-graph-sdk-5.0.0/src/Facebook/autoload.php';
	date_default_timezone_set("America/Los_Angeles");
	$fb = new Facebook\Facebook([
  				'app_id' => '1845517239059399',
  				'app_secret' => 'de8e7c04bbae8e7df7e456fe4137e145',
  				'default_access_token' => 'EAAX5TMSkNN0BACGRbuZAMRDBWMHmclITuxvkgRGGzdw0CtcxMMVcxsZCkZBpcG1LFU8Cqxxa6UYG2A9h4kfMxnfOnqFVGO47nxRpM3UBuCq7wrwSGCNcC4kc2PDqfJ3LP4vreu69cggNuUOzsRiaiYgZCgWYrLwOg2Jfc8GGZBQaDEHNXkTWFYA7ypqGZA9aIZD',
  				'default_graph_version' => 'v2.8',
			]);
	header('Content-type:application/json');
	if(isset($_GET['id'])){
		if($_GET['type']=="event"){
			$response = $fb->get('/'.$_GET['id'].'?fields=id,name,picture.width(700).height(700),posts.limit(5)');
			$data = $response->getGraphNode()->AsArray();
		}else{
			$response = $fb->get('/'.$_GET['id'].'?fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){name, picture}},posts.limit(5)');
			$data = $response->getGraphNode()->AsArray();
		}
	}
	else if($_GET['type']=="user" || $_GET['type']=="page" || $_GET['type']=="event" || $_GET['type']=="group"){
		if(isset($_GET['limit'])){
			$response = $fb->get('/search?q='.$_GET['keyword'].'&type='.$_GET['type'].'&fields=id,name,picture.width(700).height(700)&limit='.$_GET['limit']);
		}else{
			$response = $fb->get('/search?q='.$_GET['keyword'].'&type='.$_GET['type'].'&fields=id,name,picture.width(700).height(700)');
		}
		//$data = $response->getGraphEdge()->AsArray();
		$data = $response->getDecodedBody();
		$count = 0;
		/*foreach($users as $user){
			$count = $count + 1;
		}*/
		//echo data['summary']['total_count']; 
	}
	else if($_GET['type']=="place"){
		if(isset($_GET['limit'])){
			$response = $fb->get('/search?q='.$_GET['keyword'].'&type=place&fields=id,name,picture.width(700).height(700),place&center='.$_GET['center']."&limit=".$_GET['limit']);
		}else{
			$response = $fb->get('/search?q='.$_GET['keyword'].'&type=place&fields=id,name,picture.width(700).height(700),place&center='.$_GET['center']);
		}
		//$data = $response->getGraphEdge()->AsArray();
		$data = $response->getDecodedBody();
	}
	echo json_encode($data);
?>
