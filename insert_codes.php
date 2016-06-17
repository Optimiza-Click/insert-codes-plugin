<?php
/*
Plugin Name: Insert Codes Plugin
Description: Plugin para añadir códigos extra al contenido de la web.
Author: Departamento de Desarrollo - Optimizaclick
Author URI: http://www.optimizaclick.com/
Text Domain: Insert Codes Plugin
Version: 1.0
Plugin URI: http://www.optimizaclick.com/
*/

define("insert_codes_plugin_name", "insert-codes-plugin");


//FUNCION INICIAL PARA AÑADIR LA OPCION DEL PLUGIN EN EL MENU DE HERRAMIENTAS Y CARGAR OTRAS FUNCIONES
function codes_admin_menu() 
{	
	//SE AÑADE UNA OPCION EN LA BARRA DE ADMINISTRACION
	add_menu_page ( 'Insert Codes', 'Insert Codes', 'read',  'insert-codes', 'codes_form', "dashicons-chart-line", 80);
		
	//ACCION PARA REGISTRAR LAS OPCIONES DEL PLUGIN	
	add_action( 'admin_init', 'codes_optmizaclick_register_options' );	
}

//ACCION INICIAL PARA AÑADIR LA OPCION DEL PLUGIN EN EL MENU DE HERRAMIENTAS
add_action( 'admin_menu', 'codes_admin_menu' );


//FUNCION PARA MOSTRAR LAS OPCIONES DEL PLUGIN
function codes_form()
{
?><div class="wrap">

		<h1 class="title_plugin"><span>Insert Codes Plugin</span></h1>
				
		<form method="post" action="options.php">
				
		<?php settings_fields( 'insert_codes_options' ); ?>
		<?php do_settings_sections( 'insert_codes_options' ); ?>
		
		
		<h3>Elegir página: <select name="page_id" id="page_id">
		
		<?php
			
		$page_ids =get_all_page_ids();

		foreach($page_ids as $id)
		{
			echo '<option value="'.$id.'"';
			
			if($id == get_option("page_id")) echo " selected "; 
			
			echo '>'.get_the_title($id).'</option>';
		} 

	?>		
		</select> - Elegir zona de página: <select id="location_code" name="location_code">
		
		<option <?php if(get_option("location_code") == "head") echo " selected "; ?> value="head">Head</option>
		<option <?php if(get_option("location_code") == "body") echo " selected "; ?> value="body">Body</option>
		
		</select></h3>
		<h3>Código a insertar:</h3>
		
		<p><textarea id="insert_code_page" name="insert_code_page"	placeholder="Código a insertar..."><?php echo get_option("insert_code_page"); ?></textarea></p>
		
		<?php submit_button(); ?>
			
		
  </div><?php 

}   

//SE REGISTRAN TODAS LAS OPCIONES DEL PLUGIN PARA QUE SE GUARDEN EN LA TABLA OPTIONS
function codes_optmizaclick_register_options() 
{
	register_setting( 'insert_codes_options', 'page_id' );
	register_setting( 'insert_codes_options', 'insert_code_page' );
	register_setting( 'insert_codes_options', 'location_code' );
}

//ACCION PARA CARGAR ESTILOS EN LA ADMINISTRACION
add_action('admin_enqueue_scripts', "custom_codes_admin_styles");

//FUNCION PARA CARGAR ESTILOS EN EL ADMINISTRADOR
function custom_codes_admin_styles() 
{
	wp_register_style( 'custom_codes_admin_css', WP_PLUGIN_URL. '/'.insert_codes_plugin_name.'/css/style.css', false, '1.0.0' );
	wp_enqueue_style( 'custom_codes_admin_css' );
}

/// FUNCION QUE INSERTA EL CODIGO EN LA PAGINA
function insert_codes() 
{
	if(get_the_id() == get_option("page_id"))
		echo str_replace("]]&gt", "]]>", get_option("insert_code_page"));
}

//ACTION PARA AÑADIR EL CODIGO A LA PAGINA CORRESPONDIENTE
if(get_option("location_code") == "body" )
	add_action( 'wp_footer', 'insert_codes' );
else
	add_action( 'wp_head', 'insert_codes' );
?>