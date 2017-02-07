$(document).ready(function(){
  //alert('Hello World!');
});

function addField(place) {
	alert('HELLO');
	var field = document.createElement("INPUT");
	field.setAttribute("type", "text");
	field.setAttribute("name", "item");
	field.setAttribute("placeholder", "Enter your question here");
	field.setAttribute("class", "form-control");

	document.getElementById(place).appendChild(field);
}