//	**************************************************	//
//	TÉCNICO												//
//	Permite ao técnico atribuir um chamado para si, 	//
//	liberá-lo de forma a permitir que outro técnico		//
//	assuma o atendimento e também concluir o chamado.	//
//	**************************************************	//

$(document).ready(function() {
	
	$("#pickChamado, #noPickChamado, #transfChamado, #concluirChamado").hide();
	
	if($("#chamadoStatusID")){
		var statusChamado = $("#chamadoStatusID").val();
		var token = $("#token").val();
		var username = $("#usernameTecnico").val();
		var nomeUsuario = $("#usuarioNome").text();
		
		switch(statusChamado){
			case "1":	$("#pickChamado").show();
						$("#pickChamado").on('click',function(){
							$(this).off('click');
							$.ajax({
								url: "../../controller/tech/ajax/atribuirChamado.php",
								method: "POST",
								data: {
									token: token,
									acompanhamentoTecnico: nomeUsuario,
									tecnicoUsername: username,
									opcao: "pick"
								},
								success: function(ret) {
									if(ret == 1){	alert("Chamado atribuido por "+username);	location.reload();}
									if(ret == 0){	alert("Não foi possível atribuir o chamado");	}
								}
							});
							$(this).html("<img src='../../assets/images/loading.gif'></img>");
						});
						break;
					
			case "2":	$("#concluirChamado").show();
						$("#noPickChamado").show();		//	Exibe botão para "liberar" o chamado
						$("#noPickChamado").on('click',function() {
							$(this).off('click');
							$.ajax({
								url: "../../controller/tech/ajax/atribuirChamado.php",
								method: "POST",
								data: {
									token: token,
									opcao: "drop"
								},
								success: function(ret) {
									if(ret == 1){	alert("Chamado liberado");	location.reload();	}
									if(ret == 0){	alert("Não foi possível liberar o chamado");	}
								}
							});
						});
						break;
					
			case "3":	$("#transfChamado").show();
						break;
					
			default: 	break;
		}
		
		$("#concluirChamado").on('click',function(e){		//	Botão para realizar a conclusão do chamado
				e.preventDefault();
				if($("#inserirComentario").val() == ""){
					e.preventDefault();
					alert("Informe o motivo da conclusão.");
					$("#inserirComentario").focus();
				}else{
					$(this).off('click');
					$("#concluirChamado").html("<img src='../../assets/images/loading.gif'></img>");
					$("#novoInteracaoTech").attr("action", "concluirChamado.php");
					$("#novoInteracaoTech").submit();
				}
		});
		
		$("#enviarComentario").on('click',function(e){
				e.preventDefault();
				if($("#inserirComentario").val() == "" && $("#inserirAnexo").val() == ""){
					alert("Pelo menos um anexo ou mensagem deve ser inserida.");
				}else{
					$(this).off('click');
					$("#enviarComentario").html("<img src='../../assets/images/intLoading.gif'></img>");
					$("#novoInteracaoTech").attr("action", "insereInteracao.php");
					$("#novoInteracaoTech").submit();
				}
		});

		$("#inserirComentario").keyup( function() {
			var comments = $("#inserirComentario").val().length;
			if(comments >= 254) {				
				alert('O máximo de carácteres permitidos para um comentário é 255');
				$("#inserirComentario").val($("#inserirComentario").val().substring(0,254));
			}
		});
		
	}
});