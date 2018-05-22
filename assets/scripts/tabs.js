//	**************************************************	//
//	TÉCNICO	/ USUÁRIO									//
////	Permite o funcionamento das tabs do acompanha-	//
//	mento por área na home dos técnicos.				//
////	Faz com que as linhas das tabelas de acompa-	//
//	nhamento sejam clicáveis e, ao clicar, direcionar	//
//	o usuário para a página de acompanhamento do res-	//
//	pectivo chamado.									//
//	**************************************************	//

$(document).ready(function () {
	
	// busca a área de atuação do técnico através de valor do input na tela home.
	var area = document.getElementById("tecarea").value;
	
	// se a area do tecnico for diferente de 4 (gerencia), a pagina ja é aberta na janela da área correta
	// se for igual a 4, a página abre sem janela padrão, de acordo com os indices: (0 INF, 1 SIS, 2 TELE)
	var activeArea = area-1;
	
	$("#tabs").tabs({
		width: null,
		shrinkToFit: false,
		active: activeArea
	});
	
	// função responsável por abrir a página de acompanhamento referente ao chamado selecionado, através do grid de acompanhamento
	$("#tableTecnicoAcompanhamento tr, #tableInfraestruturaAcompanhamento tr, #tableSistemasAcompanhamento tr, #tableTelefoniaAcompanhamento tr").on('click',function() {
		var form = $('<form action="../../controller/tech/acchamado.php" method="get">' +
		  '<input type="hidden" name="t" value="' + $(this).attr('id') + '" />' +
		  '</form>');
		$('body').append(form);
		if($(this).attr('id') != undefined){
			$(this).off('click');
			form.submit();
		}		
	});
})