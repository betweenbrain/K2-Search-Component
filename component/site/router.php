<?php defined('_JEXEC') or die;

/**
 * File       router.php
 * Created    12/18/13 10:32 AM
 * Author     Matt Thomas | matt@betweenbrain.com | http://betweenbrain.com
 * Support    https://github.com/betweenbrain/
 * Copyright  Copyright (C) 2013 betweenbrain llc. All Rights Reserved.
 * License    GNU GPL v3 or later
 */

function K2searchBuildRoute(&$query) {

	$segments = array();

	if (isset($query['term'])) {
		$segments[] = $query['term'];
		unset($query['term']);
	}

	if (isset($query['redirect'])) {
		if ($query['redirect'] = 2) {
			unset($query['redirect']);
		}
	}

	return $segments;
}

function K2searchParseRoute($segments) {

	$vars = array();

	$vars['term'] = $segments[0];

	return $vars;
}