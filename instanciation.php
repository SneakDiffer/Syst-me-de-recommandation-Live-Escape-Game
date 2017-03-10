<?php 

	include_once ('EscapeWidget.php');
	include_once ('FeedBackWidget.php');

	class Systeme_Recommandation
	{
		public function __construct()
		{
			//Initialization widget
			add_action('widgets_init',function(){register_widget('Escape_widget');});
			add_action('widgets_init',function(){register_widget('FeedBack_widget');});
		}

		//Creation of the table on the database 
		public static function install()
		{
		    global $wpdb;

		    $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}system_recommandation_themes
					(
					ID int NOT NULL AUTO_INCREMENT,
					Name varchar(255) NOT NULL,
					PRIMARY KEY (ID)
					);");

					$wpdb->query("CREATE TABLE  IF NOT EXISTS {$wpdb->prefix}system_recommandation_criteres
					(
					ID int NOT NULL 
					AUTO_INCREMENT,
					Name varchar(255) NOT NULL,
					PRIMARY KEY(ID)
					);");
					$wpdb->query("CREATE TABLE  IF NOT EXISTS {$wpdb->prefix}system_recommandation_salles
					(
					ID int NOT NULL
					AUTO_INCREMENT,
					Name varchar(255) NOT NULL,
					lien varchar(255),
					id_theme int,
					FOREIGN KEY (id_theme)
					REFERENCES wp_system_recommandation_themes(ID),
					PRIMARY KEY(ID)    
					);");
					$wpdb->query("CREATE TABLE  IF NOT EXISTS {$wpdb->prefix}system_recommandation_notes
					(
					ID int NOT NULL
					AUTO_INCREMENT,
					note int NOT NULL 
					DEFAULT 50,
					id_critere INT,
					id_salle INT,
					FOREIGN KEY (id_critere)
					REFERENCES wp_system_recommandation_criteres(ID),
					FOREIGN KEY (id_salle)
					REFERENCES wp_system_recommandation_salles(ID),
					PRIMARY KEY(ID)
					)");
		}

		//Delet the table
		public static function uninstall()
		{
		    global $wpdb;

		    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}system_recommandation_notes;");
		    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}system_recommandation_salles;");
		    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}system_recommandation_criteres;");
		    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}system_recommandation_themes;");
		}
	}
 ?>
