<?php
/*
Plugin Name: Insert Codes Plugin
Description: Plugin para añadir códigos extra al contenido de la web.
Author: Departamento de Desarrollo - Optimizaclick
Author URI: http://www.optimizaclick.com/
Text Domain: Insert Codes Plugin
Version: 2.0
Plugin URI: http://www.optimizaclick.com/
*/

define("insert_codes_plugin_name", "insert-codes-plugin");


//FUNCION INICIAL PARA AÑADIR LA OPCION DEL PLUGIN EN EL MENU DE HERRAMIENTAS Y CARGAR OTRAS FUNCIONES
function codes_admin_menu() 
{	
	//SE AÑADE UNA OPCION EN LA BARRA DE ADMINISTRACION
	add_menu_page ( 'Insert Codes', 'Insert Codes', 'read',  'insert-codes', 'codes_form', "dashicons-chart-line", 80);
}

//ACCION INICIAL PARA AÑADIR LA OPCION DEL PLUGIN EN EL MENU DE HERRAMIENTAS
add_action( 'admin_menu', 'codes_admin_menu' );


//FUNCION PARA MOSTRAR LAS OPCIONES DEL PLUGIN
function codes_form()
{
?><div class="wrap">

		<h1 class="title_plugin"><span>Insert Codes Plugin</span></h1>
		
		<div id="accordions_content">
		
		<?php $values = get_option("insert_codes_plugin_data");
		
	
			?>
			
			<div class="accordion_full num_xx">
				<div class="accordion"><i class="dashicons dashicons-menu"></i></div>
				
				<div class="panel">
				
					<h3>Elegir página: <select name="page_id_xx" id="page_id_xx" class="page_code_id">
					
					<option value="0">Todas las páginas</option>
					
					<?php
					
						$args = array(
							'sort_order' => 'asc',
							'sort_column' => 'post_title',
							'post_type' => 'page',
							'post_status' => 'publish'
						); 
						$pages = get_pages($args); 

						foreach($pages as $page)
						{
							echo '<option value="'.$page->ID.'"';
							
							if($page->ID == $code["page_id"]) echo " selected "; 
							
							echo '>'.$page->post_title.'</option>';
						} 

					?>		
					
					</select> Elegir zona de página: <select id="location_code_xx" name="location_code_xx">
					
					<option value="head">Head</option>
					<option value="body">Body</option>
					
					</select></h3>
					<h3>Código a insertar:</h3>
					
					<p><textarea id="insert_code_page_xx" name="insert_code_page_xx" placeholder="Código a insertar..."></textarea></p>
			
					<a href="#" class="button_delete" id="del_xx">Eliminar</a>
				</div>
			</div>
			
			<?php
		
		$key = 1;
		
		foreach($values as $code)
		{
			?>
			<div class="accordion_full num_<?php echo $key; ?>">
				<div class="accordion"><i class="dashicons dashicons-menu"></i></div>
				
					<div class="panel">
					
						<h3>Elegir página: <select name="page_id_<?php echo $key; ?>" id="page_id_<?php echo $key; ?>">
						
						<option <?php if($code[0] == 0) echo " selected "; ?> value="0">Todas las páginas</option>
						
						<?php
							
							foreach($pages as $page)
							{
								echo '<option value="'.$page->ID.'"';
								
								if($page->ID == $code[0]) echo " selected "; 
								
								echo '>'.$page->post_title.'</option>';
							} 

						?>		
						
						</select> - Elegir zona de página: <select id="location_code_<?php echo $key; ?>" name="location_code_<?php echo $key; ?>">
						
						<option <?php if($code[1] == "head") echo " selected "; ?> value="head">Head</option>
						<option <?php if($code[1] == "body") echo " selected "; ?> value="body">Body</option>
						
						</select></h3>
						<h3>Código a insertar:</h3>
						
						<p><textarea id="insert_code_page_<?php echo $key; ?>" name="insert_code_page_<?php echo $key; ?>"	placeholder="Código a insertar..."><?php echo stripslashes($code[2]); ?></textarea></p>
						
						<a href="#" class="button_delete" id="del_<?php echo $key; ?>">Eliminar</a>
						
					</div>
				</div>

		<?php $key++; } ?>
		
		</div>
				
		<input type="hidden" id="url_base" value="<?php echo WP_PLUGIN_URL. '/'.insert_codes_plugin_name.'/'; ?>" />
		
		<p><a href="#" id="button_add" class="button button-primary">Nuevo código</a> <a href="#" id="button_save" class="button button-primary">Guardar cambios</a></p>
		
			
		<div id="messages_plugin"></div>
  </div><?php 

}   

//ACCION PARA CARGAR ESTILOS EN LA ADMINISTRACION
add_action('admin_enqueue_scripts', "custom_codes_admin_styles");

//FUNCION PARA CARGAR ESTILOS EN EL ADMINISTRADOR
function custom_codes_admin_styles() 
{
	wp_register_style( 'custom_codes_admin_css', WP_PLUGIN_URL. '/'.insert_codes_plugin_name.'/css/style.css', false, '1.0.0' );
	wp_enqueue_style( 'custom_codes_admin_css' );
	
	wp_enqueue_script( 'codes_script', WP_PLUGIN_URL. '/'.insert_codes_plugin_name.'/js/scripts.js', array('jquery') );
}

$original = array("]]&gt");
$changed = array("]]>");

/// FUNCION QUE INSERTA LOS CODIGOS CREADOS EN EL BODY
function insert_codes_body() 
{
	$values = get_option("insert_codes_plugin_data");
	
	foreach($values as $value)
	{
		if( $value[1] == "body")
			if(get_the_id() == $value[0] || $value[0]  == 0)
				echo stripslashes(str_replace($original, $changed, $value[2]));
	}	
}

add_action( 'wp_footer', 'insert_codes_body' );

/// FUNCION QUE INSERTA LOS CODIGOS CREADOS EN EL HEAD
function insert_codes_head() 
{
	$values = get_option("insert_codes_plugin_data");
	
	foreach($values as $value)
	{
		if( $value[1] != "body")
			if(get_the_id() == $value[0] || $value[0]  == 0)
				echo stripslashes(str_replace($original, $changed, $value[2]));
	}	
}

add_action( 'wp_head', 'insert_codes_head' );

?>