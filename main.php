<?php
	//$option = $_GET['keyword'];
	echo $_GET['id'];
	require_once __DIR__ . '/php-graph-sdk-5.0.0/src/Facebook/autoload.php';
	date_default_timezone_set("America/Los_Angeles");
	$fb = new Facebook\Facebook([
  				'app_id' => '1845517239059399',
  				'app_secret' => '051defe8fd89cd73081215ad5be3a94c',
  				'default_access_token' => 'EAAaOfPd2A8cBAGib6KzrQbX4sEZAngl8WnrFNwsv86nIjZCzsNr0MmvHpNlSrhXsxbZB7Kw6nzPZCsclLu3XsuNWLvZAEWIrY8IhC6SSRpZCASigLMoNR59JzWyuDsVJfP0pXVhcDbHrAT09EIik5lCHwji2BtI5sZD',
  				'default_graph_version' => 'v2.5',
			]);
	//$data = $fb->get('/'.$_GET['id'].'?fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){name, picture}},posts.limit(5)');
	$data = $fb->get('/'.$_GET['id'].'?fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){name, picture}},posts.limit(5)');
	header('Content-type:application/json;charset=utf-8');
	echo $data;
	//echo json_encode($data);
?>