<?php defined('_JEXEC') or die;

/**
 * File       k2search.php
 * Created    12/12/13 5:20 PM
 * Author     Matt Thomas | matt@betweenbrain.com | http://betweenbrain.com
 * Support    https://github.com/betweenbrain/
 * Copyright  Copyright (C) 2013 betweenbrain llc. All Rights Reserved.
 * License    GNU GPL v3 or later
 */

jimport('joomla.application.component.model');

class K2searchModelK2search extends JModel {

	function __construct() {
		parent::__construct();

		$this->db = JFactory::getDBO();
	}

	/**
	 * Gets the greeting
	 *
	 * @return string The greeting to be displayed to the user
	 */
	function search() {

		$term = JRequest::getVar('term');

		$query = 'SELECT * FROM ' . $this->db->nameQuote('#__k2_items') . '
				WHERE ' . $this->db->nameQuote('introtext') . '
				LIKE \'%' . $term . '%\'';

		$this->db->setQuery($query);
		$results = $this->db->loadObjectList('id');
		$count   = count($results);

		$results['results']        = new stdClass();
		$results['results']->term  = $term;
		$results['results']->count = $count;

		//die('<pre>' . print_r($results, true) . '</pre>');

		return $results;
	}
}