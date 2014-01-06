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

class K2searchModelK2search extends JModel
{

	function __construct()
	{
		parent::__construct();

		$this->db = JFactory::getDBO();
	}

	/**
	 * Performs MySQL full text search of all indexed K2 columns
	 *
	 * @return string The greeting to be displayed to the user
	 */
	function search()
	{

		$params     = & JComponentHelper::getParams('com_k2search');
		$k2category = htmlspecialchars($params->get('k2category'));

		$term               = JRequest::getVar('term');
		$results['results'] = new stdClass();

		if (!$term)
		{
			$results['results']->message = JText::_('COM_K2_SEARCH_NO_SEARCH_TERM');

			return $results;
		}

		$query = 'SELECT *,
		MATCH (
		' . $this->db->nameQuote('title') . ',
		' . $this->db->nameQuote('introtext') . ',
		' . $this->db->nameQuote('fulltext') . '
		)
		AGAINST (\'*' . $term . '*\' IN BOOLEAN MODE)
		as relevance
		FROM ' . $this->db->nameQuote('#__k2_items') . '
		WHERE ' . $this->db->nameQuote('published') . ' = 1';

		if ($k2category)
		{
			$query .= ' AND ' . $this->db->nameQuote('catid') . ' = ' . $k2category . '';
		}

		$query .= ' AND MATCH (
		' . $this->db->nameQuote('title') . ',
		' . $this->db->nameQuote('introtext') . ',
		' . $this->db->nameQuote('fulltext') . ',
		' . $this->db->nameQuote('extra_fields_search') . '
		)
		AGAINST (\'*' . $term . '*\' IN BOOLEAN MODE)
		ORDER BY relevance DESC';

		$this->db->setQuery($query);

		$results                   = $this->db->loadObjectList('id');
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
	 *
	 * @return mixed
	 */
	private function highlightTerms($term, $results)
	{
		foreach ($results as $result)
		{
			if (strpos($term, ' '))
			{
				$terms = explode(' ', $term);
				foreach ($terms as $term)
				{
					$result->introtext = preg_replace('/([a-zA-Z\s])(' . $term . ')([a-zA-Z\s])/i', '$1<i style="background: yellow">$2</i>$3', $result->introtext);
				}
			}
			$result->introtext = preg_replace('/([a-zA-Z\s])(' . $term . ')([a-zA-Z\s])/i', '$1<i style="background: yellow">$2</i>$3', $result->introtext);
		}

		return $results;
	}

	/**
	 * Processes returned results to generate a listing of tags and their count
	 *
	 * @return array
	 */
	function getTagList()
	{
		$items = $this->search();
		$tags  = array();
		foreach ($items as $item)
		{
			$itemPluginsData = parse_ini_string($item->plugins, false, INI_SCANNER_RAW);
			$item->tags      = explode(',', $itemPluginsData['tags']);

			if (isset($item->tags) && (!empty($item->tags[0])))
			{

				foreach ($item->tags as $tag)
				{
					if (isset($tags[$tag], $tags))
					{
						$tags[$tag]['count']++;
					}
					else
					{
						$tags[$tag]['count'] = 1;
					}
					$tags[$tag]['name']        = $tag;
					$tags[$tag]['compare']     = $tag;
					$tags[$tag]['alias']       = JFilterOutput::stringURLSafe($tag);

					// Truncate tag name at colon if it contains one
					$tags[$tag]['nickname'] = strpos($tag, ':') ? strstr($tag, ':', true) : $tag;
					// Shorten remaining text to three words if it has more than 4
					if (substr_count($tags[$tag]['nickname'], ' ') > 3)
					{
						$split = explode(' ', $tags[$tag]['nickname'], 4);
						unset($split[3]);
						$tags[$tag]['nickname'] = implode(' ', $split);
					}
				}
			}
		}

		return $tags;
	}

	/**
	 * Formats the raw title to comply with design requirements
	 *
	 * @param $title
	 *
	 * @return mixed|string
	 */
	function formatTitle($title)
	{
		// Trim title if longer than 60
		if (strlen($title) >= 60)
		{
			// Trim to 52 chars
			$shortTitle = substr($title, 0, 52);
			// Trim non-alphanumeric and spaces off end
			$shortTitle = preg_replace('/[^a-z0-9]+$/i', '', $shortTitle);
			// Trim string back to nearest space for whole word
			$shortTitle = preg_replace('/[^\s]+$/i', '', $shortTitle);
			// Trim off space left over and add ellipses
			$shortTitle = preg_replace('/[^a-z0-9]+$/i', '', $shortTitle) . '&hellip;';
		}
		else
		{
			$shortTitle = $title;
		}

		return $shortTitle;

	}

	/**
	 * Formats the raw results to comply with design requirements
	 *
	 * @param $result
	 *
	 * @return mixed
	 */
	function formatResults($result)
	{
		$plugins = parse_ini_string($result->plugins, false, INI_SCANNER_RAW);

		$result->tags          = strtolower(str_replace(',', ' ', $plugins['tags']));
		$result->link          = K2HelperRoute::getItemRoute($result->id . ':' . urlencode($result->alias), $result->catid . ':' . urlencode($result->category->alias));
		$result->link          = urldecode(JRoute::_($result->link));
		$result->videoDuration = $plugins['video_datavideoDuration'];
		$result->videoImage    = $plugins['video_datavideoImageUrl'];
		// Change image file name to use the smaller version
		$result->videoImage = str_replace('_902', '_280', $result->videoImage);

		return $result;
	}
}