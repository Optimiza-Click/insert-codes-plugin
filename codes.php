<?php

require_once( '../../../wp-load.php' );

if ( ! function_exists( 'update_option' ) ) 
     require_once '../../../wp-includes/option.php';

if(update_option("insert_codes_plugin_data", $_REQUEST["values"]))
	echo "Cambios guardados correctamente.";
else
	echo "¡Error al guardar los cambios!";

?>