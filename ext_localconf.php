<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

t3lib_extMgm::addService($_EXTKEY,  'rggmData' /* sv type */,  'tx_firefighter_sv1' /* sv key */,
		array(

			'title' => 'Google Maps',
			'description' => 'Google Maps for Accidents',

			'subtype' => 'tx_firefighter_accidents',

			'available' => TRUE,
			'priority' => 50,
			'quality' => 50,

			'os' => '',
			'exec' => '',

			'classFile' => t3lib_extMgm::extPath($_EXTKEY).'sv1/class.tx_firefighter_sv1.php',
			'className' => 'tx_firefighter_sv1',
		)
	);

?>