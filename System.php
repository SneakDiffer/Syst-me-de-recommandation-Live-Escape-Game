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


	/*------Fonction qui créer l'item configuration du plugin----------*/

	/*-----------------------------------------------------------------*/
	include_once ('instanciation.php');
	include_once ('Configuration.php');
	if (class_exists('Systeme_Recommandation')) {
		$object = new Systeme_Recommandation();
	}
	// get the plugin path
	$path = plugins_url();
	// then the full path to the scripts file 
	$path_to_scripts = $path . "/Systeme-de-recommandation-de-Live-Escape-Game/scripts.js";
	$path_to_style = $path . "/Systeme-de-recommandation-de-Live-Escape-Game/style.css";
	// add the custom scripts to the widget  
	wp_enqueue_script('scripts.js', $path_to_scripts);
	wp_enqueue_style('style', $path_to_style);
	//Create the table on the database when the plugin is activate
	register_activation_hook(__FILE__, array('Systeme_Recommandation', 'install'));
	//Delete the table on the database if the plugin is Uninstalled
	register_uninstall_hook(__FILE__, array('Systeme_Recommandation', 'uninstall'));
	//Plugin configuration page
	add_action('admin_menu', function(){$var = new configuration_plugin();$var->my_plugin_menu();});