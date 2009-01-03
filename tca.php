<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA["tx_firefighter_accidents"] = array (
	"ctrl" => $TCA["tx_firefighter_accidents"]["ctrl"],
	"interface" => array (
		"showRecordFieldList" => "sys_language_uid,l18n_parent,l18n_diffsource,hidden,title,date,lat,lng,rgcat,cars,type"
	),
	"feInterface" => $TCA["tx_firefighter_accidents"]["feInterface"],
	"columns" => array (
		't3ver_label' => array (		
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.versionLabel',
			'config' => array (
				'type' => 'input',
				'size' => '30',
				'max'  => '30',
			)
		),
		'sys_language_uid' => array (		
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array (
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l18n_parent' => array (		
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
				),
				'foreign_table'       => 'tx_firefighter_accidents',
				'foreign_table_where' => 'AND tx_firefighter_accidents.pid=###CURRENT_PID### AND tx_firefighter_accidents.sys_language_uid IN (-1,0)',
			)
		),
		'l18n_diffsource' => array (		
			'config' => array (
				'type' => 'passthrough'
			)
		),
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		"title" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:firefighter/locallang_db.xml:tx_firefighter_accidents.title",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"max" => "100",	
				"eval" => "required",
			)
		),
		"location" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:firefighter/locallang_db.xml:tx_firefighter_accidents.location",		
			"config" => Array (
				"type" => "input",	
				"size" => "40",	
				"max" => "100",	
			)
		),
		"description" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:firefighter/locallang_db.xml:tx_firefighter_accidents.description",		
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "RTE",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"date" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:firefighter/locallang_db.xml:tx_firefighter_accidents.date",		
			"config" => Array (
				"type"     => "input",
				"size"     => "12",
				"max"      => "20",
				"eval"     => "datetime",
				"checkbox" => "0",
				"default"  => "0"
			)
		),
		"lat" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:firefighter/locallang_db.xml:tx_firefighter_accidents.lat",		
			"config" => Array (
				"type" => "input",	
				"size" => "15",	
				"max" => "30",
			)
		),
		"lng" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:firefighter/locallang_db.xml:tx_firefighter_accidents.lng",		
			"config" => Array (
				"type" => "input",	
				"size" => "15",	
				"max" => "30",
			)
		),
		"rgcat" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:firefighter/locallang_db.xml:tx_firefighter_accidents.rgcat",		
			"config" => Array (
				"type" => "input",	
				"size" => "15",	
				"max" => "30",
			)
		),
		"cars" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:firefighter/locallang_db.xml:tx_firefighter_accidents.cars",		
			"config" => Array (
				"type" => "select",	
				"foreign_table" => "tx_firefighter_cars",	
				"foreign_table_where" => "ORDER BY tx_firefighter_cars.sorting",
				"foreign_label" => "radioname",
				"size" => 5,
				"default" => "",
				"maxitems" => 100,
			)
		),
		"type" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:firefighter/locallang_db.xml:tx_firefighter_accidents.type",		
			"config" => Array (
				"type" => "select",	
				"foreign_table" => "tx_firefighter_types",	
				"foreign_table_where" => "ORDER BY tx_firefighter_types.title",
				"foreign_label" => "title",
				"size" => 1,
			)
		),
	),
	"types" => array (
		"0" => array("showitem" => "sys_language_uid;;;;1-1-1, l18n_parent, l18n_diffsource, hidden;;1, title;;;;2-2-2, location, lat, lng, date;;;;3-3-3, type, cars, description;;;richtext[paste|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts], rgcat")
	),
	"palettes" => array (
		"1" => array("showitem" => "")
	)
);



$TCA["tx_firefighter_cars"] = array (
	"ctrl" => $TCA["tx_firefighter_cars"]["ctrl"],
	"interface" => array (
		"showRecordFieldList" => "sys_language_uid,l18n_parent,l18n_diffsource,hidden,radioname"
	),
	"feInterface" => $TCA["tx_firefighter_cars"]["feInterface"],
	"columns" => array (
		't3ver_label' => array (		
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.versionLabel',
			'config' => array (
				'type' => 'input',
				'size' => '30',
				'max'  => '30',
			)
		),
		'sys_language_uid' => array (		
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array (
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l18n_parent' => array (		
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
				),
				'foreign_table'       => 'tx_firefighter_cars',
				'foreign_table_where' => 'AND tx_firefighter_cars.pid=###CURRENT_PID### AND tx_firefighter_cars.sys_language_uid IN (-1,0)',
			)
		),
		'l18n_diffsource' => array (		
			'config' => array (
				'type' => 'passthrough'
			)
		),
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		"radioname" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:firefighter/locallang_db.xml:tx_firefighter_cars.radioname",		
			"config" => Array (
				"type" => "input",	
				"size" => "10",	
				"max" => "100",	
				"eval" => "required",
			)
		),
		"fullname" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:firefighter/locallang_db.xml:tx_firefighter_cars.fullname",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"max" => "100",	
				"eval" => "required",
			)
		),
		"link" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:firefighter/locallang_db.xml:tx_firefighter_cars.link",		
			"config" => Array (
				"type"     => "input",
				"size"     => "15",
				"max"      => "255",
				"checkbox" => "",
				"eval"     => "trim",
				"wizards"  => array(
					"_PADDING" => 2,
					"link"     => array(
						"type"         => "popup",
						"title"        => "Link",
						"icon"         => "link_popup.gif",
						"script"       => "browse_links.php?mode=wizard",
						"JSopenParams" => "height=300,width=500,status=0,menubar=0,scrollbars=1"
					)
				)
			)
		),
		"image" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:firefighter/locallang_db.xml:tx_firefighter_cars.image",		
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => "gif,png,jpeg,jpg",	
				"max_size" => 500,	
				"uploadfolder" => "uploads/tx_firefighter",
				"show_thumbs" => 1,	
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
	),
	"types" => array (
		"0" => array("showitem" => "sys_language_uid;;;;1-1-1, l18n_parent, l18n_diffsource, hidden;;1, radioname, fullname, link, image")
	),
	"palettes" => array (
		"1" => array("showitem" => "")
	)
);



$TCA["tx_firefighter_types"] = array (
	"ctrl" => $TCA["tx_firefighter_types"]["ctrl"],
	"interface" => array (
		"showRecordFieldList" => "sys_language_uid,l18n_parent,l18n_diffsource,hidden,title,icon"
	),
	"feInterface" => $TCA["tx_firefighter_types"]["feInterface"],
	"columns" => array (
		't3ver_label' => array (		
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.versionLabel',
			'config' => array (
				'type' => 'input',
				'size' => '30',
				'max'  => '30',
			)
		),
		'sys_language_uid' => array (		
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array (
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l18n_parent' => array (		
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
				),
				'foreign_table'       => 'tx_firefighter_types',
				'foreign_table_where' => 'AND tx_firefighter_types.pid=###CURRENT_PID### AND tx_firefighter_types.sys_language_uid IN (-1,0)',
			)
		),
		'l18n_diffsource' => array (		
			'config' => array (
				'type' => 'passthrough'
			)
		),
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		"title" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:firefighter/locallang_db.xml:tx_firefighter_types.title",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"max" => "100",	
				"eval" => "required",
			)
		),
		"icon" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:firefighter/locallang_db.xml:tx_firefighter_types.icon",		
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => $GLOBALS["TYPO3_CONF_VARS"]["GFX"]["imagefile_ext"],	
				"max_size" => 500,	
				"uploadfolder" => "uploads/tx_firefighter",
				"show_thumbs" => 1,	
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
	),
	"types" => array (
		"0" => array("showitem" => "sys_language_uid;;;;1-1-1, l18n_parent, l18n_diffsource, hidden;;1, title;;;;2-2-2, icon;;;;3-3-3")
	),
	"palettes" => array (
		"1" => array("showitem" => "")
	)
);
?>