<?php

require_once( '../../../wp-load.php' );

if ( ! function_exists( 'update_option' ) ) 
     require_once '../../../wp-includes/option.php';

 //SE GUARDAN LOS DATOS DE LOS CODIGOS GENERADOS PARA LA WEB!¡
if(update_option("insert_codes_plugin_data", $_REQUEST["values"]))
	echo "Cambios guardados correctamente.";
else if (get_option("insert_codes_plugin_data") == $_REQUEST["values"])
		echo "No se han realizado cambios.";
	else
		echo "¡Error al guardar los cambios!";

?>