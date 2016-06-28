jQuery(document).ready(function($)
{
	//BOTON PARA GUARDAR TODOS LOS CODIGOS GENERADOS
	jQuery("#button_save").click(function()
	{			
		if(jQuery(".accordion_full").length - 1 > 0)
			var values = new Array(jQuery(".accordion_full").length - 1);
		else
			var values = "";

		var acor = jQuery(".accordion_full").slice(1);
		
		var x = 0;

		acor.each(function(){
				
			code = new Array(5);
			
			code[0] = jQuery(this).find(".page_code_id option:selected").val();
			code[1] = jQuery(this).find(".location_code_page option:selected").val();
			code[2] = jQuery(this).find(".insert_code_page").val();
			code[3] = jQuery(this).find(".input_name_code").val();
			code[4] = jQuery(this).find(".post_type_id option:selected").val();
			
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
		
	var state_visibility = 0;
	
	//BOTON PARA DESPLEGAR/OCULTAR TODOS LOS ACORDEONES
	jQuery("#button_display").click(function()
	{	
		if(state_visibility == 0)
		{	
			jQuery(".panel").slice(1).addClass("show");
			jQuery(".accordion").slice(1).addClass("active");
			state_visibility = 1;
			
			jQuery(this).removeClass("dashicons-visibility");
			jQuery(this).addClass("dashicons-hidden");
		}
		else
		{
			jQuery(".panel").slice(1).removeClass("show");
			jQuery(".accordion").slice(1).removeClass("active");
			state_visibility = 0;
			
			jQuery(this).addClass("dashicons-visibility");
			jQuery(this).removeClass("dashicons-hidden");
		}		
	});
	
	//BOTON PARA MOSTAR EL DIALOGO QUE CONFIRMA EL BORRADO DE TODOS LOS CODIGOS GENERADOS
	jQuery("#button_empty").click(function()
	{			
		jQuery("#modal_empty").fadeIn(400);
	});
	
	//BOTON PARA ELIMINAR TODOS LOS CODIGOS GENERADOS
	jQuery("#confirm_empty").click(function()
	{	
	    jQuery(".accordion_full").slice(1).remove();
		jQuery("#modal_empty").fadeOut(400);
	});
	
	//BOTON PARA CANCELAR EL BORRRADO DE TODOS LOS CODIGOS GENERADOS
	jQuery("#cancel_empty").click(function()
	{	
		jQuery("#modal_empty").fadeOut(400);
	});
	
	//BOTON PARA MOSTAR EL DIALOGO DE AYUDA
	jQuery("#button_help").click(function()
	{			
		jQuery("#modal_help").fadeIn(400);
	});
	
	//BOTON PARA OCULTAR EL DIALOGO DE AYUDA
	jQuery("#hide_help").click(function()
	{	
		jQuery("#modal_help").fadeOut(400);
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
		
		jQuery(".button_delete, .button_duplicate, .input_name_code, .post_type_id").unbind();
		
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
				jQuery("#accordions_content").find(".accordion_full").last().find(".post_type_id").val(jQuery(this).parent().parent().find(".post_type_id").val());
				
				
				load_accordions();
			});				
		});
		
		jQuery(".post_type_id").each(function()
		{
			jQuery(this).on("change", function()
			{	
				var elemento = jQuery(this);
				
				var request = jQuery.ajax({
					  url: jQuery( "#url_base").val() + "codes.php", 
					  method: "POST",
					  data: { post_type :  jQuery("option:selected", this).val()  }	
				});
					 
				request.done(function( msg ) 
				{	
					elemento.parent().parent().find(".page_code_id").empty();
					elemento.parent().parent().find(".page_code_id").append(msg);
				});
			});
		});
	}
	
	load_accordions();

}); 