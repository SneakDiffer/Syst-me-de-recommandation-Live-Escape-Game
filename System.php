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
?>