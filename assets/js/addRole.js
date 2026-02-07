/**
 *https://ventas.programacionparacompartir.com/
 * 
 * @author tusolutionweb
 */

$(document).ready(function(){
	
	var addRoleForm = $("#addRole");
	var validatorAdd = addRoleForm.validate({
		rules:{
			status :{ required : true, selected : true},
			role : { required : true}
		},
		messages:{
			status :{ required : "Este campo es obligatorio", selected : "Por favor seleccione al menos una opcion" },
			role : { required : "Este campo es obligatorio" },
		}
	});

	var editRoleForm = $("#editRole");
	var validatorEdit = editRoleForm.validate({
		rules:{
			status :{ required : true, selected : true},
			role : { required : true}
		},
		messages:{
			status :{ required : "Este campo es obligatorio", selected : "Por favor seleccione al menos una opcion" },
			role : { required : "Este campo es obligatorio" },
		}
	});
});
