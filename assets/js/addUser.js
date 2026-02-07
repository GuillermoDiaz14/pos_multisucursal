/**
 * File : addUser.js
 * 
 * This file contain the validation of add user form
 * 
 * Using validation plugin : jquery.validate.js
 * 
 * @author Kishor Mali
 */

$(document).ready(function(){
	
	var addUserForm = $("#addUser");
	
	var validator = addUserForm.validate({
		
		rules:{
			fname :{ required : true },
			email : { required : true, email : true, remote : { url : baseURL + "checkEmailExists", type :"post"} },
			password : { required : true },
			cpassword : {required : true, equalTo: "#password"},
			mobile : { required : true, digits : true },
			role : { required : true, selected : true}
		},
		messages:{
			fname :{ required : "Este campo es obligatorio" },
			email : { required : "Este campo es obligatorio", email : "Por favor ingrese una direccion de correo electrónico valida", remote : "Email already taken" },
			password : { required : "Este campo es obligatorio" },
			cpassword : {required : "Este campo es obligatorio", equalTo: "Por favor ingrese la misma contraseña" },
			mobile : { required : "Este campo es obligatorio", digits : "Por favor solo inserte numeros" },
			role : { required : "Este campo es obligatorio", selected : "Por favor seleccione al menos una opción" }			
		}
	});
});
