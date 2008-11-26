<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

t3lib_extMgm::addPageTSConfig('
# ***************************************************************************************
# CONFIGURATION of RTE in table "tx_firefighter_accidents", field "description"
# ***************************************************************************************
RTE.config.tx_firefighter_accidents.description {
  hidePStyleItems = H1, H4, H5, H6
  proc.exitHTMLparser_db=1
  proc.exitHTMLparser_db {
    keepNonMatchedTags=1
    tags.font.allowedAttribs= color
    tags.font.rmTagIfNoAttrib = 1
    tags.font.nesting = global
  }
}
');

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