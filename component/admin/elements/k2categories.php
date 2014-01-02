<?php defined('_JEXEC') or die;

/**
 * File       k2categories.php
 * Created    1/2/14 3:02 PM
 * Author     Matt Thomas | matt@betweenbrain.com | http://betweenbrain.com
 * Support    https://github.com/betweenbrain/
 * Copyright  Copyright (C) 2014 betweenbrain llc. All Rights Reserved.
 * License    GNU GPL v3 or later
 */

class JElementK2categories extends JElement
{

	var $_name = 'K2categories';

	/**
	 * Returns an associated list of K2 categories
	 *
	 * @return mixed
	 */
	function getK2categories()
	{

		$db    = JFactory::getDbo();
		$query = ' SELECT id as value, name as text ' .
			' FROM #__k2_categories ' .
			' ORDER BY name';
		$db->setQuery($query);

		return $db->loadAssocList();;
	}

	// TODO: Add nesting to rendered select element
	/**
	 * Renders K2 categories as a select element
	 *
	 * @param $name
	 * @param $value
	 * @param $node
	 * @param $control_name
	 *
	 * @return mixed
	 */
	function fetchElement($name, $value, &$node, $control_name)
	{
		$options = $this->getK2categories();
		array_unshift($options, array('text' => '- None Selected -', 'value' => ''));

		return JHtml::_('select.genericlist', $options, '' . $control_name . '[' . $name . ']', '', 'value', 'text', $value, $control_name . $name);
	}
}