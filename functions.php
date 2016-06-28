<?php

function create_menu() {
	$menuItems = array();
	$menuItems[] = array(
		'slug'	=> 'home',
		'title'	=> 'Home'
	);
	$menuItems[] = array(
		'slug'	=> 'medications',
		'title'	=> 'Medications'
	);
	$menuItems[] = array(
		'slug'	=> 'sales',
		'title'	=> 'Sales'
	);
	$menuItems[] = array(
		'slug'	=> 'manufacturers',
		'title'	=> 'Manufacturers'
	);
	if (isset($_GET['page'])) {
    	$page = $_GET['page'];
	}

	foreach($menuItems as $menuItem) {
		$active = '';
		if ((isset($page) && $page === $menuItem['slug']) || 
			(!isset($page) && $menuItem['slug'] === 'home')) {
			$active = 'active';
		}
		echo sprintf('<a href="/pharmacy/?page=%1$s" class="item %3$s">%2$s</a>', $menuItem['slug'], $menuItem['title'], $active);
	}
}

?>