jQuery(document).ready(function($)
{
	//BOTON PARA GUARDAR TODOS LOS CODIGOS GENERADOS
	jQuery("#button_save").click(function()
	{	
		var values = new Array(jQuery(".accordion_full").length - 1);

		var acor = jQuery(".accordion_full").slice(1);
		
		var x = 0;

		acor.each(function(){
				
			code = new Array(3);
			
			code[0] = jQuery(this).find(".page_code_id option:selected").val();
			code[1] = jQuery(this).find(".location_code_page option:selected").val();
			code[2] = jQuery(this).find(".insert_code_page").val();
			
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
		var contenido = "<div class='accordion_full'>" +jQuery(".accordion_full").first().html() + "</div>";
		
		jQuery("#accordions_content").append(contenido);
		
		load_accordions();

	});
	
	//FUNCION PARA REEMPLAZAR TODAS LAS OCURRENCIAS EN UNA CADENA
	function replaceAll( html, busca, reemplaza )
	{
		while (html.indexOf(busca) != -1)
			html = html.replace(busca,reemplaza);

		return html;
	}
		
	// FUNCION PARA MOSTRAR LOS MENSAJES EN LA PAGINA DEL PLUGIN
	function view_messages(msg)
	{
		jQuery("#messages_plugin").empty();
		jQuery("#messages_plugin").html(msg);
		jQuery("#messages_plugin").fadeIn(200);
		jQuery("#messages_plugin").fadeOut(8000);
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
		
		jQuery(".button_delete").unbind();
		
		jQuery('.button_delete').each(function()
		{
			jQuery(this).click(function()
			{
				jQuery(this).parent().parent().remove();
			});
					
		});
		
	}
	
	load_accordions();

}); 