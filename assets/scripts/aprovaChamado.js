$(document).ready(function () {
	
	$("#divMotivoRejeicao").hide();
	
	if($("#chamadoStatusID").val() == 3){
		
		var token = $("#token").val();		// 	Recebe ID do chamado a ser atualizado.
		var nomeUsuario = $("#usuarioNome").text();	//	Recebe o nome do responsável pela atualização.
		var idSolucao = $("#solucaoID").val();
		
		$("#avaliacaoChamado").modal();
		
		$("#aprovaConclusao").on('click',function(){
			$(this).off('click');
			$.ajax({
				url: "../../controller/user/ajax/aprovarChamado.php",
				method: "POST",
				data: {
					token:		token,
					action:		"apr"
				},
				success: function(ret){
					if(ret == 1){	alert("Chamado aprovado e encerrado!");	location.reload();	}
					if(ret == 0){	alert("Falha na inserçao de dados");	}
				}
			})
		});
		
		$("#rejeitaConclusao").on('click',function(){
			$("#divMotivoRejeicao").toggle();
			$("#motivoRejeicao").focus();
			
			var validate;
			
			/*$("#rejeicaoChamado").validate({
				rules: {
					motivoRejeicao: {
						required: true,
						minlength: 5,
						maxlength: 500
					}
				},
				messages: {
					motivoRejeicao: {
						required: "Campo de preenchimento obrigatório",
						minlength: "Mínimo de 5 caracteres",
						maxlength: "Máximo de 500 caracteres"
					}
				}
			});*/
			
			$("#motivoRejeicao").keyup(function(){
				if($("#motivoRejeicao").val().length == 0){
					$("#motivoRejeicao_error").html("<strong>Campo de preenchimento obrigatório</strong>");
					validate = 0;
				}else if($("#motivoRejeicao").val().length < 5){
					$("#motivoRejeicao_error").html("<strong>Pelo menos 5 caracteres devem ser inseridos</strong>");
					validate = 0;
				}else if($("#motivoRejeicao").val().length > 500){
					$("#motivoRejeicao_error").html("<strong>Este campo suporta até 500 caracteres</strong>");
					validate = 0;
				}else{
					$("#motivoRejeicao_error").html("");
					validate = 1;
				}
			});
			
			$("#rejeitaConclusaoEnviar").on('click',function(){
				var motivoRejeicao = $("#motivoRejeicao").val();
				
				if(validate == 1){
					$(this).off('click');
					$.ajax({
						url: "../../controller/user/ajax/aprovarChamado.php",
						method: "POST",
						data: {
							token:				token,
							motivoRejeicao:		motivoRejeicao,
							comentarioNome:		nomeUsuario,
							solucaoID:			idSolucao,
							action:				"rej"
						},
						success: function(ret){
							if(ret == 1){	alert("O chamado foi reaberto e está aguardando atendimento.");		location.reload();	}
							if(ret == 0){	alert("Falha na inserçao de dados"); }
						}
					});
				}else{
					$("#motivoRejeicao").focus();
				}
			})
		});
	}
	
});