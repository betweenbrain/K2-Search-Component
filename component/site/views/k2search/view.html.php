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

class K2searchViewK2search extends JView {

	function display($tpl = null) {

		$model   =& $this->getModel();
		$results = $model->search();

		$this->assignRef('results', $results);

		parent::display($tpl);
	}
}