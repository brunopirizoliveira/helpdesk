/*	Valida a inserção de chamado por parte do usuário	*/

$(document).ready(function(){

	$("#novoChamadoUser").validate({
		rules: {
			novoTipo: {
				required: true,
				range: [1,4]
			},
			novoArea: {
				required: true,
				range: [1,3]
			},
			novoCategoria: {
				required: true
			},
			novoPrioridade: {
				required: true,
				range: [1,4]
			},
			novoTitulo: {
				required: true,
				minlength: 5,
				maxlength: 50
			},
			novoDescricao: {
				required: true,
				minlength: 5,
				maxlength: 2500
			}
		},
		messages: {
			novoTipo: {
				required: "Campo obrigatório",
				range: "Valor proibido"
			},
			novoArea: {
				required: "Campo obrigatório",
				range: "Valor proibido"
			},
			novoCategoria: {
				required: "Campo obrigatório"
			},
			novoPrioridade: {
				required: "Campo obrigatório",
				range: "Valor proibido"
			},
			novoTitulo: {
				required: "Campo obrigatório",
				minlength: "Digite pelo menos 5 caracteres",
				maxlength: "Este campo suporta até 50 caracteres. Reduza o tamanho do texto."
			},
			novoDescricao: {
				required: "Campo obrigatório",
				minlength: "Digite pelo menos 5 caracteres",
				maxlength: "Este campo suporta até 2500 caracteres. Reduza o tamanho do texto."
			}
		}
	});

	$("#novoEnviar").on('click',function(e){
		e.preventDefault();
		$("#novoChamadoUser").submit();
		if($("#novoChamadoUser").validate().errorList.length == 0){
			$("#novoEnviar").html("<img src='../../assets/images/intLoading.gif'></img>");
			$("#novoEnviar").off('click');
		}
	})

})