//	**************************************************	//
//	TÉCNICO												//
////	Permite ao técnico alterar a área do chamado	//
//	após sua conclusão. Altera ÁREA, CATEGORIA e STA-	//
//	TUS (para 1).										//
//	**************************************************	//

$(document).ready(function () {
	
	var area = $("#chamadoAreaID").val();
	var token = $("#token").val();
	
	$(".A"+area+", .T1, .T2, .T3").hide();
	
	$("#transfereChamadoArea").change(function(){
		escondeCategoria();
		var area = $("#transfereChamadoArea").val();
		$(".T"+area).show();
		$("#transfereChamadoCategoria").focus();
	})
	
	$("#transferirChamado").on('click',function(){
		if($("#transfereChamadoArea").val() != ""){
			if($("#transfereChamadoCategoria").val() != ""){
				$("#transferirChamado").off('click');
				$.ajax({
					url: "../../controller/tech/ajax/transfereChamado.php",
					method: "POST",
					data: {
						token:				token,
						chamadoArea:		$("#transfereChamadoArea").val(),
						chamadoCategoria:	$("#transfereChamadoCategoria").val(),
						areaDesc:			$("#transfereChamadoArea option:selected").html(),
						categoriaDesc:		$("#transfereChamadoCategoria option:selected").html()
					},
					success: function(ret){
						if(ret == 1){	alert("Chamado encaminhado para a area responsavel");	location.reload();	}
						if(ret == 0){	alert("Erro na transferência do chamado!");	}
					}
				})
			}else{
				alert("O campo CATEGORIA deve ser preenchido!");
			}
		}else{
			alert("Os campos ÁREA e CATEGORIA devem estar preenchidos");
		}
	});
	
})