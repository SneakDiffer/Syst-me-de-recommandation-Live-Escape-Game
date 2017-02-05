<?php
		/*
	Plugin Name: Systeme de recommandation
	Description: Ce plugin est une aide à la décision sur critère
	Plugin URI: https://github.com/Usanter/Systeme-de-recommandation-de-Live-Escape-Game
	Author: VILLAIN Edouard / ROLLAND Thomas / DEFONTE Véronique / BRISBARE Kevin 
	group : WeAreNotAlone
	Version: 0.1
	License: GPL2
	*/

	include_once ('plugin.class.php');
	if (class_exists('Systeme_Recommandation')) {
		$object = new Systeme_Recommandation;
	}

	//Create the table on the database when the plugin is activate
	register_activation_hook(__FILE__, array('Systeme_Recommandation', 'install'));
	//Delete the table on the database if the plugin is Uninstalled
	register_uninstall_hook(__FILE__, array('Systeme_Recommandation', 'uninstall'));
?>