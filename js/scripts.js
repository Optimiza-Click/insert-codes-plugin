jQuery(document).ready(function($)
{
	//BOTON PARA GUARDAR TODOS LOS CODIGOS GENERADOS
	jQuery("#button_save").click(function()
	{	
		jQuery(this).addClass("loading");
		
		var values = new Array(jQuery(".accordion_full").length - 1);

		var acor = jQuery(".accordion_full").slice(1);
		
		var x = 0;

		acor.each(function(){
				
			code = new Array(4);
			
			code[0] = jQuery(this).find(".page_code_id option:selected").val();
			code[1] = jQuery(this).find(".location_code_page option:selected").val();
			code[2] = jQuery(this).find(".insert_code_page").val();
			code[3] = jQuery(this).find(".input_name_code").val();
			
			values[x] = code;
			
			x++;
		});
		
		var request = jQuery.ajax({
			  url: jQuery( "#url_base").val() + "codes.php", 
			  method: "POST",
			  data: { values :  values  }	
		});
			 
		request.done(function( msg ) 
		{		
			jQuery("#button_save").removeClass("loading");
			
			view_messages(msg);

		});
	});
	
	//BOTON PARA AÑADIR UN NUEVO DESPLEGABLE PARA INSERTAR UN CODIGO NUEVO
	jQuery("#button_add").click(function()
	{	
		jQuery(".panel").slice(1).removeClass("show");
		
		jQuery(".accordion").slice(1).removeClass("active");
		
		var contenido = "<div class='accordion_full'>" +jQuery(".accordion_full").first().html() + "</div>";
		
		jQuery("#accordions_content").append(contenido);
		
		load_accordions();

	});
	
	//BOTON PARA ELIMINAR TODOS LOS CODIGOS GENERADOS
	jQuery("#button_empty").click(function()
	{	
		if(confirm("¿Eliminar todos los códigos?"))
		{
			jQuery(".accordion_full").slice(1).remove();
		}
	});
		
	// FUNCION PARA MOSTRAR LOS MENSAJES EN LA PAGINA DEL PLUGIN
	function view_messages(msg)
	{
		jQuery("#messages_plugin").empty();
		jQuery("#messages_plugin").html(msg);
		jQuery("#messages_plugin").fadeIn(200);
		jQuery("#messages_plugin").fadeOut(5000);
	}
	
	//FUNCION PARA CARGAR TODOS LOS ACORDEONES
	function load_accordions()
	{
		var accordions = jQuery(".accordion");
		var i;

		for (i = 0; i < accordions.length; i++) 
		{
			accordions[i].onclick = function()
			{
				this.classList.toggle("active");
				this.nextElementSibling.classList.toggle("show");
			}
		}
		
		jQuery(".button_delete, .button_duplicate, .input_name_code").unbind();
		
		//SE ASOCIA LA ACCION DE ELIMINAR PARA LOS BOTONES DE BORRAR CODIGO
		jQuery('.button_delete').each(function()
		{
			jQuery(this).click(function()
			{
				jQuery(this).parent().parent().remove();
			});
					
		});
		
		//SE ASOCIA LA ACCION DE ELIMINAR PARA LOS BOTONES DE BORRAR CODIGO
		jQuery('.input_name_code').each(function()
		{
			jQuery(this).keyup(function()
			{
				jQuery(this).parent().parent().parent().find(".code_name").html(jQuery(this).val());
			});
					
		});
				
		//SE ASOCIA LA ACCION DE CLONADO PARA LOS BOTONES DE DUPLICADO CODIGO
		jQuery('.button_duplicate').each(function()
		{
			jQuery(this).click(function()
			{				
				var contenido = "<div class='accordion_full'>" + jQuery(this).parent().parent().html() + "</div>";
		
				jQuery("#accordions_content").append(contenido);
				
				jQuery("#accordions_content").find(".accordion_full").last().find(".page_code_id").val(jQuery(this).parent().parent().find(".page_code_id").val());
				jQuery("#accordions_content").find(".accordion_full").last().find(".location_code_page").val(jQuery(this).parent().parent().find(".location_code_page").val());
				jQuery("#accordions_content").find(".accordion_full").last().find(".insert_code_page").val(jQuery(this).parent().parent().find(".insert_code_page").val());
				jQuery("#accordions_content").find(".accordion_full").last().find(".input_name_code").val(jQuery(this).parent().parent().find(".input_name_code").val());
				jQuery("#accordions_content").find(".accordion_full").last().find(".code_name").val(jQuery(this).parent().parent().find(".code_name").val());
				
				load_accordions();
			});
					
		});
		
	}
	
	load_accordions();

}); 