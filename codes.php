﻿<?php

require_once( '../../../wp-load.php' );
 
if(isset($_REQUEST["values"]))
{	
	if ( ! function_exists( 'update_option' ) ) 
     require_once '../../../wp-includes/option.php';
 
	 //SE GUARDAN LOS DATOS DE LOS CODIGOS GENERADOS PARA LA WEB
	if(update_option("insert_codes_plugin_data", $_REQUEST["values"]))
		echo "<span class='message_ok'>Cambios guardados correctamente.</span>";
	else if (get_option("insert_codes_plugin_data") == $_REQUEST["values"])
		echo "<span class='message_no_changes'>No se han realizado cambios.</span>";
	else
		echo "<span class='message_error'>¡Error al guardar los cambios!</span>";
}

if(isset($_REQUEST["post_type"]))
{
	if ( ! function_exists( 'get_posts' ) ) 
     require_once '../../../wp-includes/post.php';
 
	$args = array(
		'posts_per_page'   => -1,
		'sort_order' => 'asc',
		'sort_column' => 'post_title',
		'post_type' => $_REQUEST["post_type"],
		'post_status' => 'publish'
	); 
	
	$posts = get_posts($args); 

	echo '<option value="0" > --- Mostrar en Todos---</option>';

	foreach($posts as $post)
	{
		echo '<option value="'.$post->ID.'">'.$post->post_title.'</option>';
	} 
}

?>