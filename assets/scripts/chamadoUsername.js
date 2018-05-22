//	**************************************************	//
//	TÉCNICO												//
//	Ao criar um novo chamado, o técnico deve selecio-	//
//	nar o usuário que o solicitou. Para que isso seja	//
//	possível, é necessário que o técnico tenha acesso	//
//	a uma lista contendo todos os logins dos funcioná-	//
//	rios da empresa.									//
//	A lista deve estar contida em uma janela modal, 	//
//	onde o técnico poderá selecionar o login desejado.	//
//	**************************************************	//

$(document).ready(function () {
	
	$("#searchUsuario").click(function() {
		$("#modalUsuarios").modal();
		
		$("#procuraUser").keyup(function() {
			var userSearch = $(this).val().toLowerCase();

			if(userSearch != ""){
				$("#tableListaUsuarios tbody tr").hide();
				$("#tableListaUsuarios tbody tr[name*='"+userSearch+"']").show();
				$("#tableListaUsuarios tbody tr[id*='"+userSearch+"']").show();
			}else{
				$("#tableListaUsuarios tbody tr").show();
			}
		})
		
	});
	
	$("#tableListaUsuarios tbody tr").click(function() {
		$("#novoLogin").val($(this).attr("id"));
		$("#modalUsuarios").modal("hide");
	});
	
});