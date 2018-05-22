$(document).ready(function(){

	$("#enviarComentario").on('click',function(e){
		e.preventDefault();
		if($("#inserirComentario").val() == "" && $("#inserirAnexo").val() == ""){
			alert("Pelo menos um anexo ou mensagem deve ser inserida.");
		}else{
			$(this).off('click');
			$("#enviarComentario").html("<img src='../../assets/images/intLoading.gif'></img>");
			$("#novoInteracaoUser").submit();
		}
	});

})