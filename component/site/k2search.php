<?php defined('_JEXEC') or die;

/**
 * File       k2search.php
 * Created    12/12/13 3:30 PM
 * Author     Matt Thomas | matt@betweenbrain.com | http://betweenbrain.com
 * Support    https://github.com/betweenbrain/
 * Copyright  Copyright (C) 2013 betweenbrain llc. All Rights Reserved.
 * License    GNU GPL v3 or later
 */

// Require the base controller
require_once(JPATH_COMPONENT . '/controller.php');

// Create the controller
$controller = new K2searchController();

// Perform the Request task
$controller->execute(JRequest::getWord('task'));

// Redirect if set by the controller
$controller->redirect();