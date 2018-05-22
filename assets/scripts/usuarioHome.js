$(document).ready(function () {
	
	$("#tableUsuarioAcompanhamento tr").on('click',function() {
		var form = $('<form action="../../controller/user/acchamado.php" method="get">' +
		  '<input type="hidden" name="t" value="' + $(this).attr('id') + '" />' +
		  '</form>');
		$('body').append(form);
		if($(this).attr('id') != undefined){
			$(this).off('click');
			form.submit();
		}
	});
	
	
});