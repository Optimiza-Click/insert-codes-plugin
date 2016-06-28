<?php
/*
Plugin Name: Insert Codes Plugin
Description: Plugin para añadir códigos extra al contenido de la web.
Author: Departamento de Desarrollo - Optimizaclick
Author URI: http://www.optimizaclick.com/
Text Domain: Insert Codes Plugin
Version: 3.2
Plugin URI: http://www.optimizaclick.com/
*/

define("insert_codes_plugin_name", "insert-codes-plugin-master");

//FUNCION INICIAL PARA AÑADIR LA OPCION DEL PLUGIN EN EL MENU DE HERRAMIENTAS Y CARGAR OTRAS FUNCIONES
function codes_admin_menu() 
{	
	//SE AÑADE UNA OPCION EN LA BARRA DE ADMINISTRACION
	add_menu_page ( 'Insert Codes', 'Insert Codes', 'read',  'insert-codes', 'codes_form', "dashicons-editor-code", 80);
}

//ACCION INICIAL PARA AÑADIR LA OPCION DEL PLUGIN EN EL MENU DE HERRAMIENTAS
add_action( 'admin_menu', 'codes_admin_menu' );


//FUNCION PARA MOSTRAR LAS OPCIONES DEL PLUGIN
function codes_form()
{
	global $wpdb;
	
?><div class="wrap_insert_codes_plugin">

		<h1 class="insert_code_title_plugin"><i class="dashicons dashicons-editor-code icon_title"></i> <span>Insert Codes</span> </h1>
		
		<p class="menu_plugin">
			<i id="button_save" title="Guardar cambios" class="dashicons"></i>
			<i id="button_add" title="Añadir nuevo" class="dashicons dashicons-plus"></i> 
			<i id="button_empty" title="Eliminar todos" class="dashicons dashicons-trash"></i>
			<i id="button_display" title="Mostrar/ocultar todos" class="dashicons dashicons-visibility"></i> 			
						
			<span id="messages_plugin"></span>
		</p>
					
		<div id="accordions_content">
		
			<div class="accordion_full">
				<div class="accordion active"><i class="dashicons dashicons-menu"></i> <span class="code_name"></span></div>
				
				<div class="panel show">
				
					<div class="col_1_4">
				
						<input type="text" class="input_name_code" placeholder="Descripción del código" value="" /> 
					
					</div>
						
					<div class="col_1_4">
					
						<select class="post_type_id">
						<option value="all" > --- Toda la web ---</option>
						<?php
						
							$results = $wpdb->get_results( 'SELECT DISTINCT post_type FROM '.$wpdb->prefix.'posts WHERE post_status like "publish" order by 1 asc'  );
							
							foreach ( $results as $row )
							{
								echo '<option value="'.$row->post_type.'">'.ucfirst ($row->post_type).'</option>';
							} 

						?>		
						</select>
		
					</div>
						
					<div class="col_1_4">
					
						<select class="page_code_id">
						
						<option value="0" > --- Insertar en todos---</option>
						
						</select> 
						
					</div>
						
					<div class="col_1_4">

						<select class="location_code_page">
						<option value="-1" > --- Cargar código en ---</option>
						<option value="head">Head</option>
						<option value="before_content">Before Content</option>
						<option value="after_content">After Content</option>
						<option value="footer">Footer</option>
						
						</select>
					
					</div>
				
					<p><textarea class="insert_code_page" placeholder="Código a insertar..."></textarea></p>
			
					<i class="button_duplicate dashicons dashicons-screenoptions"></i> <i class="button_delete dashicons dashicons-no"></i>
				</div>
			</div>
			
			<?php
				
		$values = get_option("insert_codes_plugin_data");		
			
		if($values != "")
		{
			foreach($values as $code)
			{
				?>
				<div class="accordion_full num_<?php echo $key; ?>">
					<div class="accordion"><i class="dashicons dashicons-menu"></i> <span class="code_name"><?php echo $code[3]; ?></span></div>
					
						<div class="panel">
						
							<div class="col_1_4">
								<input type="text" class="input_name_code" placeholder="Descripción del código" value="<?php echo $code[3]; ?>" /> 
							</div>
							
							<div class="col_1_4">
							<select class="post_type_id">
								<option value="all" > --- Toda la web ---</option>
								<?php
									
									foreach ( $results as $row )
									{
										echo '<option value="'.$row->post_type.'"';
										
										if($row->post_type == $code[4]) echo " selected "; 
										
										echo '>'.ucfirst ($row->post_type).'</option>';
									} 

								?>		
							</select>
							
							</div>
							
							<div class="col_1_4">
							
								<select class="page_code_id">

								<option <?php if($code[0] == 0) echo " selected "; ?> value="0">--- Insertar en todos---</option>
								
								<?php
									
									$args = array(
										'sort_order' => 'asc',
										'sort_column' => 'post_title',
										'post_type' => $code[4],
										'post_status' => 'publish'
									); 
									
									$posts = get_posts($args); 

									foreach($posts as $post)
									{
										echo '<option value="'.$post->ID.'"';
										
										if($post->ID == $code[0]) echo " selected "; 
																	
										echo '>'.$post->post_title.'</option>';
									} 

								?>		
								
								</select> 
								
								</div>
							
								<div class="col_1_4">
							
								<select class="location_code_page">
									<option value="-1" > --- Cargar código en ---</option>
									<option <?php if($code[1] == "head") echo " selected "; ?> value="head">Head</option>
									<option <?php if($code[1] == "before_content") echo " selected "; ?> value="before_content">Before Content</option>
									<option <?php if($code[1] == "after_content") echo " selected "; ?> value="after_content">After Content</option>
									<option <?php if($code[1] == "footer") echo " selected "; ?> value="footer">Footer</option>
								</select>
							
							</div>
							
							<p><textarea class="insert_code_page"	placeholder="Código a insertar..."><?php echo stripslashes($code[2]); ?></textarea></p>
							
							<i class="button_duplicate dashicons dashicons-screenoptions"></i> <i class="button_delete dashicons dashicons-no"></i>
							
						</div>
					</div>

		<?php } } ?>
		
		</div>
		
		<div id="modal_empty">
		
			<h3>¿Borrar todos los códigos insertados?</h3>
			<a href="#" id="confirm_empty">Aceptar</a> <a href="#" id="cancel_empty">Cancelar</a>
		</div>
		
		<div id="modal_help">
		
			<h2>Uso del plugin</h2>
			<p>Este plugin permite insertar tantos códigos como sea necesario. Podemos especificar que los códigos se ejecuten en toda la web o solo en tipos de post concretos (páginas, entradas, productos...) y la zona de la página (head, content, footer).</p>
			<p>Se puede insertar tanto código HTML, como CSS (introduciendo los estilos entre las etiquetas &ltstyle&gt &lt/style&gt), Javascript, códigos de Google Analytics, códigos de conversión, pixels de Facebook... e incluso shortcodes de Wordpress.</p>
			<p>Hay que tener cuidado al especificar en qué zona de la página se ejecuta el código introducido ya que en algunos casos puede suponer que la web no funcione.
			En el caso de los códigos de conversión para garantizar que funcionen se deberá indicar que se ejecuten en el footer.</p>
			
			<p>Disponemos de las siguientes opciones:</p>
			<ul>		
				<li><i class="dashicons icon-save"></i> Botón para guardar los códigos insertados con sus correspondientes configuraciones.</li>
				<li><i class="dashicons dashicons-plus"></i>  Botón para generar un formulario para insertar un nuevo código.</li>
				<li><i class="dashicons dashicons-trash"></i> Botón para eliminar todos los códigos generados. Hasta que no se guarden los cambios no serán borrados definitivamente.</li>
				<li><i class="dashicons dashicons-visibility"></i> Botón para desplegar/ocultar todos los formularios con los códigos generados.</li>
			</ul>
			
			<p>Por cada elemento generado para insertar un código tendremos además 2 acciones:</p>
			
			<ul>
				<li><i class="dashicons dashicons-screenoptions"></i> Botón para crear un duplicado del mismo.</li>
				<li><i class="dashicons dashicons-no"></i> Botón para eliminar ese elemento concreto.</li>
			</ul>
			
			<i id="hide_help" class="dashicons dashicons-no"></i>
		</div>
		
		<i id="button_help" class="dashicons dashicons-editor-help"></i>
				
		<input type="hidden" id="url_base" value="<?php echo WP_PLUGIN_URL. '/'.insert_codes_plugin_name.'/'; ?>" />
		
		<style>div.notice, .update-nag, #wpfooter, #message{display: none !important;}</style>
				
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
function insert_codes_body($content) 
{
	$content = load_codes("before_content") . $content . load_codes("after_content");
	
	return $content;
}

add_filter( 'the_content', 'insert_codes_body' );

/// FUNCION QUE INSERTA LOS CODIGOS CREADOS EN EL FOOTER
function insert_codes_footer() 
{
	echo load_codes("footer");
}

add_action( 'wp_footer', 'insert_codes_footer');

/// FUNCION QUE INSERTA LOS CODIGOS CREADOS EN EL HEAD
function insert_codes_head() 
{
	echo load_codes("head");
}

add_action( 'wp_head', 'insert_codes_head' );

/// FUNCION QUE INSERTA LOS CODIGOS
function load_codes($zone)
{
	$result = "";
	
	$original = array("]]&gt");
	$changed = array("]]>");
		
	$values = get_option("insert_codes_plugin_data");
		
	foreach($values as $value)
	{
		if($value[4] == "all" || $value[4] == get_post_type(get_the_id()))
			if( $value[1] == $zone)
				if(get_the_id() == $value[0] || $value[0]  == 0)
					$result .= $value[2];
	}	
	
	return str_replace($original, $changed, do_shortcode(stripslashes ($result)));
}

?>