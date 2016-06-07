function selected(frm)
{	
	for(i = 0; i < frm.options.length; i++)
	{
		frm.options[i].selected = true;	
	}
}

function hasOptions(obj) 
{
	if (obj!=null && obj.options!=null) {return true;}
	return false;
}
				
function sortSelect(obj) 
{
	var o = new Array();
	if(!hasOptions(obj)) {return;}
	for (var i=0; i<obj.options.length; i++) 
	{
		o[o.length] = new Option( obj.options[i].text, obj.options[i].value, obj.options[i].defaultSelected, obj.options[i].selected) ;
	}
	if(o.length==0){return;}
	o = o.sort( 
				function(a,b) 
				{ 
					if ((a.text+"") < (b.text+"")) {return -1;}
					if ((a.text+"") > (b.text+"")) {return 1;}
					return 0;
				} 
		);

	for(var i=0; i<o.length; i++) 
	{
		obj.options[i] = new Option(o[i].text, o[i].value, o[i].defaultSelected);
	}
}

		
function moveSelectedOptions(from,to) 
{
	// Move them over
	if(!hasOptions(from)) {return;}
	for(var i=0; i<from.options.length; i++) 
	{
		var o = from.options[i];
		if(o.selected) 
		{
			if(!hasOptions(to)) 
				{var index = 0;} 
			else
				{var index=to.options.length;}
		
		to.options[index] = new Option(o.text, o.value, o.selected);
		}
	}
	
	// Delete them from original
	for(var i=(from.options.length-1); i>=0; i--) 
	{
		var o = from.options[i];
		if(o.selected) 
		{
			from.options[i] = null;
		}
	}
	if((arguments.length<3) || (arguments[2]==true)) 
	{
		sortSelect(from);
		sortSelect(to);
	}
		
	from.selectedIndex = -1;
	to.selectedIndex = -1;
}		

		
function moveAllOptions(from,to) 
{
	selectAllOptions(from);
	if(arguments.length==2) 
	{
		moveSelectedOptions(from,to);
	}
	else if(arguments.length==3) 
	{
		moveSelectedOptions(from,to,arguments[2]);
	}
}


function selectAllOptions(obj) 
{
	if (!hasOptions(obj)) {return;}
	for (var i=0; i<obj.options.length; i++) 
	{
		obj.options[i].selected = true;
	}
}