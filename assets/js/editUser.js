/**
 * File : editUser.js 
 * 
 * This file contain the validation of edit user form
 * 
 * @author Kishor Mali
 */
$(document).ready(function(){
	
	var editUserForm = $("#editUser");
	
	var validator = editUserForm.validate({
		
		rules:{
			fname :{ required : true },
			email : { required : true, email : true, remote : { url : baseURL + "checkEmailExists", type :"post", data : { userId : function(){ return $("#userId").val(); } } } },
			cpassword : {equalTo: "#password"},
			mobile : { required : true, digits : true },
			role : { required : true, selected : true}
		},
		messages:{
			fname :{ required : "Este campo es obligatorio" },
			email : { required : "Este campo es obligatorio", email : "Por favor ingrese una dirección de correo electrónico valida", remote : "Email already taken" },
			cpassword : {equalTo: "Please enter same password" },
			mobile : { required : "Este campo es obligatorio", digits : "Por favor solo inserte numeros" },
			role : { required : "Este campo es obligatorio", selected : "Por favor seleccione al menos una opcion" }			
		}
	});

	var editProfileForm = $("#editProfile");
	
	var validator = editProfileForm.validate({
		
		rules:{
			fname :{ required : true },
			mobile : { required : true, digits : true },
			email : { required : true, email : true, remote : { url : baseURL + "checkEmailExists", type :"post", data : { userId : function(){ return $("#userId").val(); } } } },
		},
		messages:{
			fname :{ required : "Este campo es obligatorio" },
			mobile : { required : "Este campo es obligatorio", digits : "Por favor solo inserte numeros" },
			email : { required : "Este campo es obligatorio", email : "Por favor ingrese una dirección email valida", remote : "Email ya tomado" },
		}
	});

});