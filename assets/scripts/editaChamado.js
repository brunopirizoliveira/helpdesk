//	**************************************************	//
//	TECNICO												//
//	Quando o usuário incluir um novo chamado com in-	//
//	formações incorretas, o técnio deve ser capaz de	//
//	corrigir estas informações.							//
//	**************************************************	//

$(document).ready(function () {
	
	var tipo = $("#chamadoTipoID").val();
	var area = $("#chamadoAreaID").val();
	var categoria = $("#chamadoCategoriaID").val();
	var prioridade = $("#chamadoPrioridadeID").val();
	
	$(".alterarOn").hide();
	
	if($("#chamadoStatusID").val() > 2){
		$("#editaChamado").hide();
	}
	
	$("#editaChamado, #editaCancela").click(function() {
		toggleEdicao();
	});
	
	escondeCategoria();
	
	exibeTipo(tipo);
	exibeArea(area);
	exibeCategoria(area, categoria);
	exibePrioridade(prioridade);
	
	
	$("#editaChamadoArea").change( function() {
		escondeCategoria();
		exibeCategoria(this.value);
	});
	
	$("#editaSalvar").on('click', function(){

		var token = $("#token").val();
		var checksum = 0;
		
		if($("#editaChamadoTipo").val() != null){	checksum++;	}
		if($("#editaChamadoArea").val() != null){	checksum++;	}
		if($("#editaChamadoCategoria").val() != null){	checksum++;	}
		if($("#editaChamadoPrioridade").val() != null){	checksum++;	}
		
		if(checksum == 4){
			$(this).off('click');
			$.ajax({
				url: "../../controller/tech/ajax/editarChamado.php",
				method: "POST",
				data: {
					token:				token,
					chamadoTipo: 		$("#editaChamadoTipo").val(),
					chamadoArea: 		$("#editaChamadoArea").val(),
					chamadoCategoria: 	$("#editaChamadoCategoria").val(),
					chamadoPrioridade:	$("#editaChamadoPrioridade").val()
				},
				success: function(ret){
					if(ret == 1){	alert("Alteracoes realizadas");	location.reload();		}
					if(ret == 0){	alert("Não foi possível editar o chamado "+idChamado);	}
				}
			})
		}else{
			alert("Todos os campos devem estar preenchidos.");
		}
	});
	
});