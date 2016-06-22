<?php
/*
Plugin Name: Insert Codes Plugin
Description: Plugin para añadir códigos extra al contenido de la web.
Author: Departamento de Desarrollo - Optimizaclick
Author URI: http://www.optimizaclick.com/
Text Domain: Insert Codes Plugin
Version: 2.4
Plugin URI: http://www.optimizaclick.com/
*/

define("insert_codes_plugin_name", "insert-codes-plugin-master");


//FUNCION INICIAL PARA AÑADIR LA OPCION DEL PLUGIN EN EL MENU DE HERRAMIENTAS Y CARGAR OTRAS FUNCIONES
function codes_admin_menu() 
{	
	//SE AÑADE UNA OPCION EN LA BARRA DE ADMINISTRACION
	add_menu_page ( 'Insert Codes', 'Insert Codes', 'read',  'insert-codes', 'codes_form', "dashicons-media-code", 80);
}

//ACCION INICIAL PARA AÑADIR LA OPCION DEL PLUGIN EN EL MENU DE HERRAMIENTAS
add_action( 'admin_menu', 'codes_admin_menu' );


//FUNCION PARA MOSTRAR LAS OPCIONES DEL PLUGIN
function codes_form()
{
?><div class="wrap_insert_codes_plugin">

		<h1 class="insert_code_title_plugin"><i class="dashicons dashicons-media-code icon_title"></i> <span>Insert Codes</span></h1>
		
		<p class="menu_plugin"><i id="button_add" class="dashicons dashicons-plus"></i> <i id="button_empty" class="dashicons dashicons-trash"></i> <i id="button_save" class="dashicons dashicons-update"></i></p>
		
		<div id="accordions_content">
		
		<?php $values = get_option("insert_codes_plugin_data");
	
			?>
			
			<div class="accordion_full">
				<div class="accordion active"><i class="dashicons dashicons-menu"></i></div>
				
				<div class="panel show">
				
					<h3>Elegir página: <select class="page_code_id">
					
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
					
					</select> <span class="text_h3">Cargar código en:</span> <select class="location_code_page">
					
					<option value="head">Head</option>
					<option value="body">Body</option>
					
					</select></h3>
				
					<p><textarea class="insert_code_page" placeholder="Código a insertar..."></textarea></p>
			
					<i class="button_delete dashicons dashicons-no"></i>
				</div>
			</div>
			
			<?php
				
		foreach($values as $code)
		{
			?>
			<div class="accordion_full num_<?php echo $key; ?>">
				<div class="accordion"><i class="dashicons dashicons-menu"></i></div>
				
					<div class="panel">
					
						<h3>Elegir página: <select class="page_code_id">
						
						<option <?php if($code[0] == 0) echo " selected "; ?> value="0">Todas las páginas</option>
						
						<?php
							
							foreach($pages as $page)
							{
								echo '<option value="'.$page->ID.'"';
								
								if($page->ID == $code[0]) echo " selected "; 
								
								echo '>'.$page->post_title.'</option>';
							} 

						?>		
						
						</select> <span class="text_h3">Cargar código en:</a> <select class="location_code_page">
						
						<option <?php if($code[1] == "head") echo " selected "; ?> value="head">Head</option>
						<option <?php if($code[1] == "body") echo " selected "; ?> value="body">Body</option>
						
						</select></h3>
						
						<p><textarea class="insert_code_page"	placeholder="Código a insertar..."><?php echo stripslashes($code[2]); ?></textarea></p>
						
						<i class="button_delete dashicons dashicons-no"></i>
						
					</div>
				</div>

		<?php } ?>
		
		</div>
				
		<input type="hidden" id="url_base" value="<?php echo WP_PLUGIN_URL. '/'.insert_codes_plugin_name.'/'; ?>" />
				
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

/// FUNCION QUE INSERTA LOS CODIGOS CREADOS EN EL BODY
function insert_codes_body() 
{
	load_codes("body");
}

add_action( 'wp_footer', 'insert_codes_body' );

/// FUNCION QUE INSERTA LOS CODIGOS CREADOS EN EL HEAD
function insert_codes_head() 
{
	load_codes("head");
}

add_action( 'wp_head', 'insert_codes_head' );

$original = array("]]&gt");
$changed = array("]]>");

/// FUNCION QUE INSERTA LOS CODIGOS
function load_codes($zone)
{
	$values = get_option("insert_codes_plugin_data");
	
	foreach($values as $value)
	{
		if( $value[1] == $zone)
			if(get_the_id() == $value[0] || $value[0]  == 0)
				echo stripslashes(str_replace($original, $changed, $value[2]));
	}	
}

?>