function showContainer(idContainer, url)
{
	$('#main_'+idContainer).show();
	$('#max_'+idContainer).hide();
	$('#loading'+idContainer).show();
	$.ajaxSetup({  
	     contentType : "",  
	     beforeSend : function(xml){  
		 // xml.setRequestHeader('Accept','*/*');  
		 xml.setRequestHeader('Content-Type','application/x-www-form-urlencoded');  
		 xml.setRequestHeader("charset","utf-8");  
		 xml.setRequestHeader("Encoding","iso-8859-1");  
		 xml.setRequestHeader("X-Requested-With", "");  
	     }  
	});  
	$.post(url, 
		function(data)
		{ 
			if(data.search(/<?php echo $this->labels->login->title ?>/i) != -1)
			{
				window.location.reload();
			}
			else
			{
				$('#'+idContainer).append(data);
				$('#loading'+idContainer).hide();
			}
		}
	);
}

function hideContainer(idContainer)
{
	$('#'+idContainer).empty();
	$('#main_'+idContainer).hide();
	$('#max_'+idContainer).show();
}
