<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 Sven Weiss <sfeni@sfeni.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'Einsatz Liste' for the 'firefighter' extension.
 *
 * @author	Sven Weiss <sfeni@sfeni.de>
 * @package	TYPO3
 * @subpackage	tx_firefighter
 */
class tx_firefighter_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_firefighter_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_firefighter_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'firefighter';	// The extension key.
	var $pi_checkCHash = true;
	var $ffVars = Array();
	var $templates;
	
	/**
	 * Main method of your PlugIn
	 *
	 * @param	string		$content: The content of the PlugIn
	 * @param	array		$conf: The PlugIn Configuration
	 * @return	The content that should be displayed on the website
	 */
	function main($content,$conf)	{
		switch((string)$conf['CMD'])	{
			case 'singleView':
				list($t) = explode(':',$this->cObj->currentRecord);
				$this->internal['currentTable']=$t;
				$this->internal['currentRow']=$this->cObj->data;
				return $this->pi_wrapInBaseClass($this->singleView($content,$conf));
			break;
			default:
				if (strstr($this->cObj->currentRecord,'tt_content'))	{
					$conf['pidList'] = $this->cObj->data['pages'];
					$conf['recursive'] = $this->cObj->data['recursive'];
				}
				return $this->listView($content,$conf);
			break;
		}
	}
	
	/**
	 * Shows a list of database entries
	 *
	 * @param	string		$content: content of the PlugIn
	 * @param	array		$conf: PlugIn Configuration
	 * @return	HTML list of table entries
	 */
	function listView($content,$conf)	{
		$this->conf=$conf;		// Setting the TypoScript passed to this function in $this->conf
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();		// Loading the LOCAL_LANG values
		$this->pi_initPIflexform();
		
		// read flexform settings
		$piFlexForm = $this->cObj->data['pi_flexform'];
		$index = $GLOBALS['TSFE']->sys_language_uid;
		$sDef = current($piFlexForm['data']);       
		$lDef = array_keys($sDef);
		$this->ffVars['detailviewPID'] = $this->pi_getFFvalue($piFlexForm, 'detailview', 'sDEF', $lDef[$index]);
		$this->ffVars['template'] = $this->pi_getFFvalue($piFlexForm, 'template_file', 'sDEF', $lDef[$index]);
		$this->ffVars['max'] = $this->pi_getFFvalue($piFlexForm, 'max_anzahl', 'sDEF', $lDef[$index]);
		$this->ffVars['show_search'] = $this->pi_getFFvalue($piFlexForm, 'show_search', 'display', $lDef[$index]);
		$this->ffVars['date_from'] = $this->pi_getFFvalue($piFlexForm, 'date_from', 'sDEF', $lDef[$index]);
		$this->ffVars['date_to'] = $this->pi_getFFvalue($piFlexForm, 'date_to', 'sDEF', $lDef[$index]);
		
		
		// read template
		$templateCode = $this->cObj->fileResource($this->ffVars['template']);
		$this->templates = array();
		$this->templates["list_table"] = $this->cObj->getSubpart($templateCode, "###LIST_TABLE###");
		$this->templates["list_row"] = $this->cObj->getSubpart($this->templates["list_table"], "###LIST_TABLE_ROW###");
		$this->templates["geo"] = $this->cObj->getSubpart($this->templates["list_row"], "###GEO_SCRIPT###");
		$this->templates["single_view"] = $this->cObj->getSubpart($templateCode, "###SINGLE_VIEW###");
		$this->templates["single_view_caritem"] = $this->cObj->getSubpart($this->templates["single_view"], "###CAR_ITEM###");
		$this->templates["geo_single"] = $this->cObj->getSubpart($this->templates["single_view"], "###GEO_SCRIPT###");

		$lConf = $this->conf['listView.'];	// Local settings for the listView function
	
		if ($this->piVars['showUid'])	{	// If a single element should be displayed:
			$this->internal['currentTable'] = 'tx_firefighter_einsatz';
			$this->internal['currentRow'] = $this->pi_getRecord('tx_firefighter_einsatz',$this->piVars['showUid']);
	
			$content = $this->singleView($content,$conf);
			return $content;
		} else {
			$items=array(
				'1'=> $this->pi_getLL('list_mode_1','Mode 1'),
				'2'=> $this->pi_getLL('list_mode_2','Mode 2'),
				'3'=> $this->pi_getLL('list_mode_3','Mode 3'),
			);
			if (!isset($this->piVars['pointer']))	$this->piVars['pointer']=0;
			if (!isset($this->piVars['mode']))	$this->piVars['mode']=1;
	
				// Initializing the query parameters:
			$count = '999999';
			if($this->ffVars['max']>0) $count = $this->ffVars['max'];
			$this->internal['orderBy'] = "datefrom";
			$this->internal['descFlag'] = "DESC";
			$this->internal['results_at_a_time']=t3lib_div::intInRange($lConf['results_at_a_time'],0,1000,$count);		// Number of results to show in a listing.
			$this->internal['maxPages']=t3lib_div::intInRange($lConf['maxPages'],0,1000,999);;		// The maximum number of "pages" in the browse-box: "Page 1", "Page 2", etc.
			$this->internal['searchFieldList']='title,location,description';
			$this->internal['orderByList']='uid,title,location';
			$where = "";
			if($this->ffVars['date_from'] > 1) $where .= " AND `datefrom`>'" . $this->ffVars['date_from'] . "' ";
			if($this->ffVars['date_to'] > 1) $where .= " AND `datefrom`<'" . $this->ffVars['date_to'] . "' ";
	
				// Get number of records:
			$res = $this->pi_exec_query('tx_firefighter_einsatz',1, $where);
			list($this->internal['res_count']) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
	
				// Make listing query, pass query to SQL database:
			#$res = $this->pi_exec_query('tx_firefighter_einsatz');
			$res = $this->pi_exec_query('tx_firefighter_einsatz', 0, $where, '', '', 'datefrom DESC');
			$this->internal['currentTable'] = 'tx_firefighter_einsatz';
	
				// Put the whole list together:
			$fullTable='';	// Clear var;
		#	$fullTable.=t3lib_div::view_array($this->piVars);	// DEBUG: Output the content of $this->piVars for debug purposes. REMEMBER to comment out the IP-lock in the debug() function in t3lib/config_default.php if nothing happens when you un-comment this line!
		#	$fullTable.=t3lib_div::view_array($this->ffVars);
		
				// Adds the mode selector.
			//$fullTable.=$this->pi_list_modeSelector($items);
	
				// Adds the whole list table
			$fullTable.=$this->makelist($res);
	
				// Adds the search box:
			if($this->ffVars['show_search']) $fullTable.=$this->pi_list_searchBox();
	
				// Adds the result browser:
			//$fullTable.=$this->pi_list_browseresults();
	
				// Returns the content from the plugin.
			return utf8_encode($fullTable);
		}
	}
	/**
	 * Creates a list from a database query
	 *
	 * @param	ressource	$res: A database result ressource
	 * @return	A HTML list if result items
	 */
	function makelist($res)	{
		$items="";
		$counter = $this->internal['res_count'];
		while($this->internal['currentRow'] = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$items .= $this->makeListItem($counter);
			$counter--;
		}

		$subpartArray = array();
		$subpartArray["###LIST_TABLE_ROW###"] = $items;
		$out = $this->cObj->substituteMarkerArrayCached($this->templates["list_table"], array(), $subpartArray, array());
		return $out;
	}
	
	/**
	 * Implodes a single row from a database to a single line
	 *
	 * @return	HTML of a single database entry
	 */
	function makeListItem($counter)	{
		$markerArray = array();
		$markerArray["###NR###"] = $counter;
		$markerArray["###TITLE###"] = $this->getFieldContent('title');
		$markerArray["###LOCATION###"] = $this->getFieldContent('location');
		$markerArray["###DATE_BEGIN###"] = $this->getFieldContent('datefrom');
		$geo_coords = $this->getFieldContent('geo_coords');
		$markerArray["###GEO###"] = $geo_coords;
		
		$type_row = $this->pi_getRecord('tx_firefighter_type', $this->getFieldContent('type'));
		$icon = "";
		if($type_row['icon'] != "") $icon = "uploads/tx_firefighter/" . $type_row['icon'];
		$markerArray["###TYPE_ICON###"] = $icon;

		$geo_content = "";
		if($geo_coords != "") {
			$geo_content = $this->cObj->substituteMarkerArrayCached($this->templates["geo"], $markerArray, array(), array());
		}
		$subpartArray = array();
		$subpartArray["###GEO_SCRIPT###"] = $geo_content;

		$out = $this->cObj->substituteMarkerArrayCached($this->templates["list_row"], $markerArray, $subpartArray, array());
		
		return $out;
	}
	/**
	 * Display a single item from the database
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	HTML of a single database entry
	 */
	function singleView($content,$conf)	{
		$this->conf=$conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();

		// This sets the title of the page for use in indexed search results:
		if ($this->internal['currentRow']['title']) $GLOBALS['TSFE']->indexedDocTitle=$this->internal['currentRow']['title'];

		$markerArray = array();
		$markerArray["###TITLE###"] = $this->getFieldContent('title_wo_link');
		$markerArray["###LOCATION###"] = $this->getFieldContent('location');
		$markerArray["###DATE_BEGIN###"] = $this->getFieldContent('datefrom');
		$markerArray["###DATE_END###"] = $this->getFieldContent('dateto');
		$markerArray["###DESCRIPTION###"] = $this->getFieldContent('description');
		$markerArray["###CREATED###"] = date('d.m.Y H:i',$this->internal['currentRow']['tstamp']);
		$markerArray["###EDIT_PANEL###"] = $this->pi_getEditPanel();
		$carslist = $this->getFieldContent('cars');
		$geo_coords = $this->getFieldContent('geo_coords');
		$markerArray["###GEO###"] = $geo_coords;

		$this->internal['currentTable'] = 'tx_firefighter_type';
		$this->internal['currentRow'] = $this->pi_getRecord('tx_firefighter_type',$this->getFieldContent('type'));
		$markerArray["###TYPE###"] = $this->internal['currentRow']['name'];
		$icon = "";
		if($this->internal['currentRow']['icon'] != "") $icon = "uploads/tx_firefighter/" . $this->internal['currentRow']['icon'];
		$markerArray["###TYPE_ICON###"] = $icon;

		$car_content = "";
		$this->internal['orderBy'] = "sorting";
		$this->internal['descFlag'] = "ASC";
		$this->internal['currentTable'] = 'tx_firefighter_cars';
		$res = $this->pi_exec_query('tx_firefighter_cars', 0, " AND `uid` IN ($carslist)");
		while($this->internal['currentRow'] = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$markerArray2 = array();
			$markerArray2["###NAME###"] = $this->getFieldContent('name');
			$markerArray2["###IMAGE###"] = "uploads/tx_firefighter/" . $this->getFieldContent('image');
			$link = $this->getFieldContent('link');
			if(is_numeric($link)) $link = $this->pi_getPageLink($link);
			$markerArray2["###LINK###"] = $link;
			
			$car_content .= $this->cObj->substituteMarkerArrayCached($this->templates["single_view_caritem"], $markerArray2, array(), array());
		}

		$geo_content = "";
		if($geo_coords != "") {
			$geo_content = $this->cObj->substituteMarkerArrayCached($this->templates["geo_single"], $markerArray, array(), array());
		}

		$subpartArray = array();
		$subpartArray["###CAR_ITEM###"] = $car_content;
		$subpartArray["###GEO_SCRIPT###"] = $geo_content;

		$content = $this->cObj->substituteMarkerArrayCached($this->templates["single_view"], $markerArray, $subpartArray, array());
		return utf8_encode($content);
	}
	/**
	 * Returns the content of a given field
	 *
	 * @param	string		$fN: name of table field
	 * @return	Value of the field
	 */
	function getFieldContent($fN)	{
		$return_val = "";
		
		switch($fN) {
			case 'uid':
				$return_val = $this->pi_list_linkSingle($this->internal['currentRow'][$fN],$this->internal['currentRow']['uid'],1);	// The "1" means that the display of single items is CACHED! Set to zero to disable caching.
			break;
			case "datefrom":
				$return_val = strftime('%d.%m.%Y %H:%M',$this->internal['currentRow']['datefrom']);
			break;
			case "dateto":
				$return_val = strftime('%d.%m.%Y %H:%M',$this->internal['currentRow']['dateto']);
			break;
			case "title":
					// This will wrap the title in a link.
				$return_val = $this->pi_list_linkSingle($this->internal['currentRow']['title'],$this->internal['currentRow']['uid'],1,'','',$this->ffVars['detailviewPID']); //
			break;
			case "title_wo_link":
				$return_val = $this->internal['currentRow']['title'];
			break;
			case "description":
				$return_val = $this->pi_RTEcssText($this->internal['currentRow']['description']);
			break;
			default:
				$return_val = $this->internal['currentRow'][$fN];
			break;
		}
		
		if($this->internal['currentRow']['tstamp'] < 1224525137) {
			return $return_val;
		} else {
			return utf8_decode($return_val);
		}
	}
	/**
	 * Returns the label for a fieldname from local language array
	 *
	 * @param	[type]		$fN: ...
	 * @return	[type]		...
	 */
	function getFieldHeader($fN)	{
		switch($fN) {
			case "title":
				return $this->pi_getLL('listFieldHeader_title','<em>title</em>');
			break;
			default:
				return $this->pi_getLL('listFieldHeader_'.$fN,'['.$fN.']');
			break;
		}
	}
	
	/**
	 * Returns a sorting link for a column header
	 *
	 * @param	string		$fN: Fieldname
	 * @return	The fieldlabel wrapped in link that contains sorting vars
	 */
	function getFieldHeader_sortLink($fN)	{
		return $this->pi_linkTP_keepPIvars($this->getFieldHeader($fN),array('sort'=>$fN.':'.($this->internal['descFlag']?0:1)));
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/firefighter/pi1/class.tx_firefighter_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/firefighter/pi1/class.tx_firefighter_pi1.php']);
}

?>