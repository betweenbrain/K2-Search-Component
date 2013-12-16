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
	 * Performs MySQL full text search of all indexed K2 columns
	 *
	 * @return string The greeting to be displayed to the user
	 */
	function search() {

		$params     = & JComponentHelper::getParams('com_k2search');
		$k2category = $params->get('k2category');

		$term               = JRequest::getVar('term');
		$results['results'] = new stdClass();

		if (!$term) {
			$results['results']->message = 'Please enter a search term';

			return $results;
		}

		$query = 'SELECT *
		FROM ' . $this->db->nameQuote('#__k2_items') . '
		WHERE ' . $this->db->nameQuote('catid') . ' = ' . $k2category . '
		AND MATCH (' . $this->db->nameQuote('title') . ',
		' . $this->db->nameQuote('introtext') . ',
		' . $this->db->nameQuote('fulltext') . ',
		' . $this->db->nameQuote('extra_fields_search') . ',
		' . $this->db->nameQuote('image_caption') . ',
		' . $this->db->nameQuote('image_credits') . ',
		' . $this->db->nameQuote('video_caption') . ',
		' . $this->db->nameQuote('video_credits') . ',
		' . $this->db->nameQuote('metadesc') . ',
		' . $this->db->nameQuote('metakey') . '
		)
		AGAINST (\'*' . $term . '*\' IN BOOLEAN MODE)';

		$this->db->setQuery($query);

		$results                   = $this->db->loadObjectList('id');
		$results                   = $this->highlightTerms($term, $results);
		$count                     = count($results);
		$results['results']->term  = $term;
		$results['results']->count = $count;

		return $results;
	}

	/**
	 * Rudimentary term highlighting
	 *
	 * TODO: Current regex looks only for alpha chars and spaces to avoid html matches.
	 *
	 * @param $term
	 * @param $results
	 * @return mixed
	 */
	private function highlightTerms($term, $results) {

		foreach ($results as $result) {
			if (strpos($term, ' ')) {
				$terms = explode(' ', $term);
				foreach ($terms as $term) {
					$result->introtext = preg_replace('/([a-zA-Z\s])(' . $term . ')([a-zA-Z\s])/i', '$1<i style="background: yellow">$2</i>$3', $result->introtext);
				}
			}
			$result->introtext = preg_replace('/([a-zA-Z\s])(' . $term . ')([a-zA-Z\s])/i', '$1<i style="background: yellow">$2</i>$3', $result->introtext);
		}

		return $results;
	}
}