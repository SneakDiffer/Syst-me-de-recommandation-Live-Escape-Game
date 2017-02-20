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

		    $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}Recommendation_system (id_salle INT  PRIMARY KEY,nom_salle VARCHAR(255) NOT NULL, score INT NOT NULL);");
		   
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

		    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}Recommendation_system;");
		}


	}
 ?>