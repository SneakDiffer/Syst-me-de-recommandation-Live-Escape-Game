<?php 
	/**
	* Plugin Main Class
	*/

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
		   
		   	$test = fopen('/Applications/MAMP/htdocs/wordpress/wp-content/plugins/Systeme-de-recommandation-de-Live-Escape-Game/test.txt', 'a+');
		   	$file = file_get_contents('critere.conf', FILE_USE_INCLUDE_PATH);
			fputs($test, $file);
		    /*if ($file = fopen("/Applications/MAMP/htdocs/wordpress/wp-content/plugins/Systeme-de-recommandation-de-Live-Escape-Game/critere.conf","a+"))
		    {
		    	while(!feof($file))
		    	{
		    	 	$line = file_get_contents($file);
		    	 	//$line = "coucou";
		    	 	fputs($test, $line); // On écrit le nouveau nombre de pages vues
		    	 	$wpdb->query("ALTER TABLE {$wpdb->prefix}Recommendation_system ADD $line INT;");
		    	}
				fclose($file);
		    }*/
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
