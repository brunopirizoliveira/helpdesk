//	**************************************************	//
//	TÉCNICO / USUÁRIO									//
//	Ao selecionar uma área, o select de categoria deve	//
//	exibir somente as categorias inerentes à área se-	//
//	lecionada.											//
//	**************************************************	//

$(document).ready(function () {
	
	// Chama a função escondeCategoria() a fim posicionar o select no índice 0 (vazio) e esconder as categorias específicas
	escondeCategoria();
	
	// Ao selecionar uma área, o select de categoria será alterado de forma a mostrar somente o que for correspondente à área selecionada
	$("#novoArea").change( function() {
		escondeCategoria();
		exibeCategoria(this.value);
	});
	
	/*$("#enviarFiltro").click( function() {
		var areaChamado = document.getElementById("areaChamado[]").value;
		alert(areaChamado);
	});*/
	
});