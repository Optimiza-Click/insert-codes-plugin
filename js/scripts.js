jQuery(document).ready(function($)
{
	//BOTON PARA CREAR PAGINA DE AVISO LEGAL
	jQuery("#button_save").click(function()
	{	
		var values = new Array(jQuery(".accordion").length);

		var x = 0;
		
		var acor = jQuery(".accordion");

		acor.each(function(){
				
			code = new Array(3);
			
			code[0] = jQuery("#page_id_"+ x +" option:selected").val();
			code[1] = jQuery("#location_code_"+ x +" option:selected").val();
			code[2] = jQuery("#insert_code_page_"+ x).val();
			
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
	
	jQuery("#button_add").click(function()
	{	
		var contenido = "<div class='accordion_full num_xx'>" +jQuery(".accordion_full").first().html() + "</div>";
		
		jQuery("#accordions_content").append(replaceAll(contenido, "_xx", "_"+ jQuery(".accordion").length));
		
		load_accordions();

	});
	
	function replaceAll( html, busca, reemplaza )
	{
		while (html.indexOf(busca) != -1)
			html = html.replace(busca,reemplaza);

		return html;
	}
		
	function view_messages(msg)
	{
		jQuery("#messages_plugin").empty();
		jQuery("#messages_plugin").html(msg);
		jQuery("#messages_plugin").fadeIn(200);
		jQuery("#messages_plugin").fadeOut(8000);
	}
	
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