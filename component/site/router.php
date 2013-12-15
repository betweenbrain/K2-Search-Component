<?php

/**
 * File            router.php
 * Created        12/15/13 8:04 AM
 * Author        Matt Thomas matt@betweenbrain.com
 * Copyright    Copyright (C) 2013 betweenbrain llc.
 */

function K2searchBuildRoute(&$query) {

	$segments = array();
	if (isset($query['view'])) {
		$segments[] = $query['view'];
		unset($query['view']);
	}
	if (isset($query['id'])) {
		$segments[] = $query['id'];
		unset($query['id']);
	};

	return $segments;
}

function K2searchParseRoute($segments) {

	$vars = array();
	switch ($segments[0]) {
		case 'categories':
			$vars['view'] = 'categories';
			break;
		case 'category':
			$vars['view'] = 'category';
			$id           = explode(':', $segments[1]);
			$vars['id']   = (int) $id[0];
			break;
		case 'article':
			$vars['view'] = 'article';
			$id           = explode(':', $segments[1]);
			$vars['id']   = (int) $id[0];
			break;
	}

	return $vars;
}