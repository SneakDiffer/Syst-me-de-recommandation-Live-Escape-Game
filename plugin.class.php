<?php 
	/**
	* Plugin Main Class
	*/

	include_once ('EscapeWidget.php');

	class Systeme_Recommandation
	{
		
		public function __construct()
		{
			//Initialization widget
			add_action('widgets_init',function(){register_widget('Escape_widget');});
		}

		//Creation of the table on the database 
		public static function install()
		{
		    global $wpdb;

		    $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}Recommendation_system2 (id_salle INT  PRIMARY KEY, score INT NOT NULL);");
		   
		    if ($file = fopen("critere.conf","w+"))
		    {
		    	while(!feof($file))
		    	{
		    	 	$line = fgets($file);
		    	 	$wpdb->query("ALTER TABLE {$wpdb->prefix}Recommendation_system2 ADD $line INT;");
		    	}
				fclose($file);
		    }
		}

		//Delet the table
		public static function uninstall()
		{
		    global $wpdb;

		    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}Recommendation_system;");
		}
	}
 ?>