
// Responds when a radio button is clicked.
function RadReact()
{
document.write("retard");

var radios = document.getElementsByTagName('input');
var tarea;
var temp;

for(var i = 0; i < radios.length; i++) 
	{
	//Make sure we only are dealing with input objects of type radio -- radio buttons.
	if (radios[i].type == 'radio')
		{
    	radios[i].onclick = function() 
			{
			//This gets the index of the series array and puts it into the textarea when the textarea is clicked.
			//I want to get the javascript array value in here instead,
			//but I can't figure out how to get it from php into javascript.
			temp = this.value;
			tarea = document.getElementById('cssarea');
			// If we haven't loaded the array of css sheets for some reason, get out.
			if ( typeof my_json_object == 'undefined' )
				return;
			//Otherwise, assign the value of the textarea to its css sheet info.
			tarea.value = my_json_object[temp];
    		}
		}
	}
};


