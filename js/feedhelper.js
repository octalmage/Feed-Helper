jQuery(document).on("ready", function()
{
	jQuery("#feedhelper_go").on("click", function()
	{
	  	jQuery("#feedhelper_go").attr("disabled", true);
	  	jQuery("#feedhelper_go").val("Scanning...");
	  	
		jQuery.post(ajaxurl, {'action' : 'feedhelper_findnewlines'}, function(response) 
		{
		  	jQuery("#feedhelper_results").html(response);
		  	jQuery("#feedhelper_go").attr("disabled", false);
	  		jQuery("#feedhelper_go").val("Start");
		});
	});
})