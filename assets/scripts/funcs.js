//	**************************************************	//
//	TECNICO / USUARIO									//
//	Este arquivo contem funções que são utilizadas em	//
//	mais de um código JavaScript.						//
//	**************************************************	//

//	Responsável por resetar o select de categoria quando a área é alterada.
function escondeCategoria(){
	$("#novoCategoria, #editaChamadoCategoria, #transfereChamadoCategoria").prop("selectedIndex", 0);
	$(".1, .T1, .2, .T2, .3, .T3").hide();
};

//	Responsável por exibir o tipo do chamado no select de edição.
function exibeTipo(tipo){
	if(document.getElementById("editaChamadoTipo")){
		document.getElementById("editaChamadoTipo").value = tipo;
	}
};

//	Responsável por exibir a área do chamado no select de edição.
//	Esta função recorre à função exibeCategoria, para que a categoria correta também seja exibida.
function exibeArea(area){
	if(document.getElementById("editaChamadoArea")){
		document.getElementById("editaChamadoArea").value = area;
		exibeCategoria(area, 0);
	}
};

//	Função responsável por exibir a categoria do chamado no select de edição.
function exibeCategoria(area, categoria){
	$("."+area).show();
	if(categoria != 0){
		if(document.getElementById("editaChamadoCategoria")){
			document.getElementById("editaChamadoCategoria").value = categoria;
		}
	}
};

//	Função responsável por exibir a prioridade do chamado no select de edição.
function exibePrioridade(prioridade){
	if(document.getElementById("editaChamadoPrioridade")){
		document.getElementById("editaChamadoPrioridade").value = prioridade;
	}
};

//	Função responsável por mostrar/esconder a div de edição do chamado.
function toggleEdicao(){
	$(".alterarOn, .alterarOff").toggle();
};

//	Função responsável por exibir o pop-over com guia das categorias, na página Novo chamado.
$('[data-toggle="popover"]').popover({html: true, placement: "bottom", viewport: {selector: "#wrapper", padding: 0}});   