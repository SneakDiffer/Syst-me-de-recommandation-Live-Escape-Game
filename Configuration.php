<?php	
class configuration_plugin {

	function my_plugin_menu() {
				add_options_page( 'Configuration du plugin Escape Game', 'Configuration Escape Game', 'manage_options', None, array( $this, 'my_plugin_options' ) );
	}

	function my_plugin_options() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		?>
		<div class="wrap">
		<h1 class="wp-heading-inline">Veuillez configurer votre plugin</h1><br/><br/><br/>
		<table class="wp-list-table widefat fixed striped pages">
		<thead>
			<tr>
		       <td><a class="row-title"><span>Nom salle </span></a></td>
				<?php
				global $wpdb;
				$result = $wpdb->get_results( "SELECT Name FROM {$wpdb->prefix}system_recommandation_criteres");
				foreach ( $result as $row ) 
				{
				?>
					<td><a class="row-title"><span><?php echo $row->Name ?></span></a></td>
				<?php
				}
				?>
		       <td><a class="row-title"><span>Thème </span></a></td>
		       <td><a class="row-title"><span>Lien </span></a></td>
		   	</tr>
		   	<?php
		   	$result = $wpdb->get_results( "SELECT Name FROM {$wpdb->prefix}system_recommandation_salles");
		   	foreach ( $result as $row ) 
			{
			?>
					<tr>
						<td><?php echo $row->Name ?></td>
						<td>50</td>
						<td>50</td>
						<td>50</td>
						<td>50</td>
						<td>
							<SELECT name="theme" size="1">
							<OPTION>lundi
							<OPTION>mardi
							<OPTION>mercredi
							<OPTION>jeudi
							<OPTION>vendredi
							</SELECT>
						</td>
					</tr>
			<?php
			}
		   	?>
		</thead> 
		</table>
		</div>
		<br/><input type="submit" id="update-table" class="button" value="Modifier la table">
		<?php
	}
}