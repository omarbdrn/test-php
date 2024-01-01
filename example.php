#!/usr/bin/php
<?php
	require_once 'class.php';

	$api_key = "123456789";
	
	$facebook_asset = array(
		'host' 		=> "facebook.com",
	);

	$google_asset = array(
		'host' 		=> "google.com",
	);

	$test_scan = array(
		'name' 		=> "Scan Example",
		'template' => array(
			'id'			=> "ca733466-87cf-4247-a70e-a1acbc5f273e",						// String UUID
			'frequency'		=> "semi_daily",			// Frequency (semi_daily, daily, weekly, monthly)
		),
		'agent' => array(
			'id'			=> "e5d22ff4-22c2-427a-971b-61a475c66bba",					// String UUID
		),
		'actions' => array(
			array(
				'callback_url'		=> "https://api.integhub.internal/scan_callback",
				'trigger'			=> "new_service",	// Triggers refer to datastruct.php
			),
		),
		'assets' => array(
			array(
				'host'		=> "facebook.com",
				),
			array(
				'host'		=> "google.com",
			)
		)
	);

	$colego = new Colego($api_key, "SANDBOX");

	$colego->createAsset($facebook_asset);
	$colego->createAsset($google_asset);

	$jsonData = $colego->createScan($test_scan);
	echo $jsonData;

	$scans = $colego->listScans();
	echo $scans;

	echo $colego->getScan($colego->getScanHash($jsonData););
?>
