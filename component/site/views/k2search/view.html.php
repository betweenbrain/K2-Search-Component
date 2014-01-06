<?php defined('_JEXEC') or die;

/**
 * File       view.html.php
 * Created    12/12/13 3:32 PM
 * Author     Matt Thomas | matt@betweenbrain.com | http://betweenbrain.com
 * Support    https://github.com/betweenbrain/
 * Copyright  Copyright (C) 2013 betweenbrain llc. All Rights Reserved.
 * License    GNU GPL v3 or later
 */

jimport('joomla.application.component.view');

/**
 * HTML View class for the HelloWorld Component
 *
 * @package    HelloWorld
 */

class K2searchViewK2search extends JView
{

	function display($tpl = null)
	{

		$model         = $this->getModel();
		$params        = & JComponentHelper::getParams('com_k2search');
		$showSearchbox = $params->get('showSearchbox');
		$results       = $model->search();
		$tagList       = $model->getTagList();

		$this->assignRef('results', $results);
		$this->assignRef('tags', $tagList);
		$this->assignRef('showSearchbox', $showSearchbox);

		parent::display($tpl);
	}
}