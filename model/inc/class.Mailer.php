<?php

	DEFINE('__ROOT__',DIRNAME(DIRNAME(DIRNAME(__FILE__))));
	REQUIRE_ONCE(__ROOT__.'/model/inc.autoload.php');

	CLASS Mailer{

		PRIVATE $mail;
		PRIVATE $header;
	
		PUBLIC FUNCTION __CONSTRUCT(){
			$mail = NEW PHPMailer();
			
			$mail->IsSMTP();                                      	// set mailer to use SMTP
			$mail->Host = "smtp..com.br";  			// specify main and backup server
			$mail->SMTPAuth = TRUE;     							// turn on SMTP authentication
			$mail->Username = "imp@.com.br";  		// SMTP username
			// $mail->Password = "1q2w3e4r";							// SMTP password correto
			$mail->Password = "";							// SMTP password
			$mail->From = "no-reply@.com.br";
			$mail->FromName = "Help Desk";
			$mail->WORDWRAP = 50;									// set word wrap to 50 characters
			$mail->IsHTML(TRUE);									// set email format to HTML
			
			$this->mail = $mail;
								
			$this->data = date('d/m/Y H:i:s');
		}
	
		PUBLIC FUNCTION mailUser_Pick($chamadoToken){
			$mail = $this->mail;
		
			$chamadosDAO	= new ChamadosDAO();
			$adLDAP 		= new adLDAP();
			
			$chamado = $chamadosDAO->chamadoInfo($chamadoToken);
			$chamadoID = $chamado->getChamadoID();
			$chamadoNome = $chamado->getChamadoNome();
			$chamadoTecnico = $chamado->getAcompanhamentoTecnico();
			
			$chamadoUsername = $chamado->getChamadoUsername();
			$info = $adLDAP->user()->info($chamadoUsername,array("mail"));
		
			$mail->AddAddress($info[0]['mail'][0],$chamadoNome);

			$mail->Subject = UTF8_DECODE("[HELPDESK #".$chamadoID."] Chamado em atendimento");
			$mail->Body    = UTF8_DECODE(
							"		<img src='http://192.168.1.42/helpdesk/assets/images/logo.gif' alt=''></img>	<h2>Help Desk</h2>
								<br>Chamado #".$chamadoID."
								<br>
								<br>O técnico <strong>".$chamadoTecnico."</strong> é o responsável pelo seu chamado.
								<br>
								<br>Acesse seu chamado clicando <a href='http://192.168.1.42/helpdesk/controller/user/acchamado.php?t=".$chamadoToken."'>aqui</a>.
								<br>
								<br>Este email não deve ser respondido");
								
			$mail->AltBody = UTF8_DECODE(
							 "	Chamado #".$chamadoID."
								
								O técnico ".$chamadoTecnico." é o responsável pelo seu chamado.
								
								Este email não deve ser respondido");

			$mail->Send();
			
		}
		
		PUBLIC FUNCTION mailUser_Conclusao($chamadoToken){

			$chamadosDAO	= new ChamadosDAO();
			$adLDAP 		= new adLDAP();
			
			$chamado = $chamadosDAO->chamadoInfo($chamadoToken);
			$chamadoID = $chamado->getChamadoID();
			$chamadoNome = $chamado->getChamadoNome();
			$chamadoTecnico = $chamado->getAcompanhamentoTecnico();
			
			$chamadoUsername = $chamado->getChamadoUsername();
			$info = $adLDAP->user()->info($chamadoUsername,array("mail"));
			
			for($m=0;$m<2;$m++){
				if($m==0){
					$mailUser = $this->mail;
					$mailUser->clearAllRecipients();
					#	Envio ao requerente	#
				
					$mailUser->AddAddress($info[0]['mail'][0],$chamadoNome);
					
					$mailUser->Subject = UTF8_DECODE("[HELPDESK #".$chamadoID."] Chamado concluído");
					$mailUser->Body    = UTF8_DECODE(
										"		<img src='http://192.168.1.42/helpdesk/assets/images/logo.gif' alt=''></img>	<h2>Help Desk</h2>
											<br>Chamado #".$chamadoID."
											<br>
											<br>Seu chamado foi concluido pelo tecnico <strong>".$chamadoTecnico."</strong> em ".$this->data.".
											<br>Acesse seu chamado através do Helpdesk e avalie a conclusão.
											<br>O chamado será automaticamente encerrado se não houver contato dentro de 10 dias.
											<br>
											<br>Acesse seu chamado clicando <a href='http://192.168.1.42/helpdesk/controller/user/acchamado.php?t=".$chamadoToken."'>aqui</a>.
											<br>
											<br>Este email não deve ser respondido");
											
					$mailUser->AltBody = UTF8_DECODE( 
										"	Chamado #".$chamadoID."
								
											Seu chamado foi concluido pelo tecnico <strong>".$chamadoTecnico."</strong> em ".$this->data.".
											Acesse seu chamado através do Helpdesk e avalie a conclusão.
											O chamado será automaticamente encerrado se não houver contato dentro de 10 dias.
								
											Este email não deve ser respondido");

					$mailUser->Send();
				}
				if($m==1){
					$mailTec = $this->mail;
					$mailTec->clearAllRecipients();
					#	Envio à equipe de suporte	#
					
					$mailTec->AddAddress("bruno_oliveira@henriquestefani.com.br","Suporte");
					
					$mailTec->Subject = UTF8_DECODE("[HELPDESK #".$chamadoID."] Chamado concluído");
					$mailTec->Body    = UTF8_DECODE(
										"		<img src='http://192.168.1.42/helpdesk/assets/images/logo.gif' alt=''></img>	<h2>Help Desk</h2>
											<br>Chamado #".$chamadoID."
											<br>
											<br>Chamado concluido pelo tecnico <strong>".$chamadoTecnico."</strong> em ".$this->data.".
											<br>Aguarda avaliação do requerente.
											<br>
											<br>Acesse o chamado clicando <a href='http://192.168.1.42/helpdesk/controller/tech/acchamado.php?t=".$chamadoToken."'>aqui</a>.
											<br>
											<br>Este email não deve ser respondido");
					$mailTec->AltBody = UTF8_DECODE(
										"	Chamado #".$chamadoID."
											
											Chamado concluido pelo tecnico <strong>".$chamadoTecnico."</strong> em ".$this->data.".
											Aguarda avaliação do requerente.
											
											Este email não deve ser respondido");

					$mailTec->Send();
				}
			}
		}
		
		PUBLIC FUNCTION mail_Encerramento($chamadoToken){
		
			$chamadosDAO	= new ChamadosDAO();
			$adLDAP 		= new adLDAP();
			
			$chamado = $chamadosDAO->chamadoInfo($chamadoToken);
			$chamadoID = $chamado->getChamadoID();
			$chamadoNome = $chamado->getChamadoNome();
			
			$chamadoUsername = $chamado->getChamadoUsername();
			$info = $adLDAP->user()->info($chamadoUsername,array("mail"));
			
			for($m=0;$m<2;$m++){
				if($m==0){
					$mailUser = $this->mail;
					$mailUser->clearAllRecipients();
					#	Envio ao requerente	#
				
					$mailUser->AddAddress($info[0]['mail'][0],$chamadoNome);
					
					$mailUser->Subject = UTF8_DECODE("[HELPDESK #".$chamadoID."] Chamado encerrado");
					$mailUser->Body    = UTF8_DECODE(
										"		<img src='http://192.168.1.42/helpdesk/assets/images/logo.gif' alt=''></img>	<h2>Help Desk</h2>
											<br>Chamado #".$chamadoID."
											<br>
											<br>Seu chamado foi encerrado em ".$this->data.".
											<br>Para mais informações, acesse seu chamado no Helpdesk.
											<br>
											<br>Acesse seu chamado clicando <a href='http://192.168.1.42/helpdesk/controller/user/acchamado.php?t=".$chamadoToken."'>aqui</a>.
											<br>
											<br>Este email não deve ser respondido");
					$mailUser->AltBody = UTF8_DECODE(
										"	Chamado #".$chamadoID."
											
											Seu chamado foi encerrado em ".$this->data.".
											Para mais informações, acesse seu chamado no Helpdesk.
											
											Este email não deve ser respondido");

					$mailUser->Send();
				}
				if($m==1){
					$mailTec = $this->mail;
					$mailTec->clearAllRecipients();
					#	Envio à equipe de suporte	#
					
					$mailTec->AddAddress("bruno_oliveira@henriquestefani.com.br","Suporte");
					
					$mailTec->Subject = UTF8_DECODE("[HELPDESK #".$chamadoID."] Chamado encerrado");
					$mailTec->Body    = UTF8_DECODE(
										"		<img src='http://192.168.1.42/helpdesk/assets/images/logo.gif' alt=''></img>	<h2>Help Desk</h2>
											<br>Chamado #".$chamadoID."
											<br>
											<br>Chamado encerrado em ".$this->data.".
											<br>
											<br>Acesse seu chamado clicando <a href='http://192.168.1.42/helpdesk/controller/tech/acchamado.php?t=".$chamadoToken."'>aqui</a>.
											<br>
											<br>Este email não deve ser respondido");
					$mailTec->AltBody = UTF8_DECODE(
										"	Chamado #".$chamadoID."
											
											Chamado encerrado em ".$this->data.".
											
											Este email não deve ser respondido");

					$mailTec->Send();
				}
			}
		}
		
		PUBLIC FUNCTION mail_Interacao($chamadoToken, $chamadoComentario, $comentarioNome){
		
			$chamadosDAO	= new ChamadosDAO();
			$adLDAP 		= new adLDAP();
			
			$chamado = $chamadosDAO->chamadoInfo($chamadoToken);
			$chamadoID = $chamado->getChamadoID();
			
			$chamadoUsername = $chamado->getChamadoUsername();
			$info = $adLDAP->user()->info($chamadoUsername,array("mail"));
			
			for($m=0;$m<2;$m++){
				if($m==0){
					$mailUser = $this->mail;
					$mailUser->clearAllRecipients();
					#	Envio ao requerente	#
				
					$mailUser->AddAddress($info[0]['mail'][0],$chamadoNome);
					
					$mailUser->Subject = UTF8_DECODE("[HELPDESK #".$chamadoID."] Nova interação");
					$mailUser->Body    = UTF8_DECODE(
										"		<img src='http://192.168.1.42/helpdesk/assets/images/logo.gif' alt=''></img>	<h2>Help Desk</h2>
											<br>Chamado #".$chamadoID."
											<br>
											<br>Houve nova interação no seu chamado.
											<br><strong>Data:</strong> ".$this->data."
											<br><strong>Autor:</strong> ".$comentarioNome."
											<br><strong>Interação:</strong> ".$chamadoComentario."
											<br>
											<br>Para mais informações, acesse seu chamado no Helpdesk clicando <a href='http://192.168.1.42/helpdesk/controller/user/acchamado.php?t=".$chamadoToken."'>aqui</a>.
											<br>
											<br>Este email não deve ser respondido");
					$mailUser->AltBody = UTF8_DECODE(
										"	Chamado #".$chamadoID."
											
											Houve nova interação no seu chamado.
											Data: ".$this->data."
											Autor: ".$comentarioNome."
											Interação: ".$chamadoComentario."
											Para mais informações, acesse seu chamado no Helpdesk.
											
											Este email não deve ser respondido");

					$mailUser->Send();
				}
				if($m==1){
					$mailTec = $this->mail;
					$mailTec->clearAllRecipients();
					#	Envio à equipe de suporte	#
					
					$mailTec->AddAddress("bruno_oliveira@henriquestefani.com.br","Suporte");
					
					$mailTec->Subject = UTF8_DECODE("[HELPDESK #".$chamadoID."] Nova interação");
					$mailTec->Body    = UTF8_DECODE(
										"		<img src='http://192.168.1.42/helpdesk/assets/images/logo.gif' alt=''></img>	<h2>Help Desk</h2>
											<br>Chamado #".$chamadoID."
											<br>
											<br>Houve nova interação no chamado.
											<br><strong>Data:</strong> ".$this->data."
											<br><strong>Autor:</strong> ".$comentarioNome."
											<br><strong>Interação:</strong> ".$chamadoComentario."
											<br>
											<br>Para mais informações, acesse seu chamado no Helpdesk clicando <a href='http://192.168.1.42/helpdesk/controller/tech/acchamado.php?t=".$chamadoToken."'>aqui</a>.
											<br>
											<br>Este email não deve ser respondido");
					$mailTec->AltBody = UTF8_DECODE(
										"	Chamado #".$chamadoID."
											
											Houve nova interação no chamado.
											Data: ".$this->data."
											Autor: ".$comentarioNome."
											Interação: ".$chamadoComentario."
											
											Este email não deve ser respondido");

					$mailTec->Send();
				}
			}
		}
		
		PUBLIC FUNCTION mail_Criacao($chamadoToken){	
		
			$chamadosDAO	= new ChamadosDAO();
			$adLDAP 		= new adLDAP();
			
			$chamado 			= $chamadosDAO->chamadoInfo($chamadoToken);
			$chamadoID 			= $chamado->getChamadoID();
			$chamadoTitulo 		= $chamado->getChamadoTitulo();
			$chamadoPrioridade 	= $chamado->getChamadoPrioridade();
			$chamadoNome 		= $chamado->getChamadoNome();
			$chamadoTipo		= $chamado->getChamadoTipo();
			$chamadoArea 		= $chamado->getChamadoArea();
			$chamadoCategoria	= $chamado->getChamadoCategoria();
			$chamadoDescricao	= $chamado->getChamadoDescricao();
			
			$chamadoUsername = $chamado->getChamadoUsername();
			$info = $adLDAP->user()->info($chamadoUsername,array("mail"));
					
			for($m=0;$m<2;$m++){
				if($m==0){
					$mailUser = $this->mail;
					$mailUser->clearAllRecipients();
					#	Envio ao requerente	#
					$mailUser->AddAddress($info[0]['mail'][0],$chamadoNome);
					
					$mailUser->Subject = UTF8_DECODE("[HELPDESK #".$chamadoID."] Chamado criado");
					$mailUser->Body    = UTF8_DECODE( 
										"		<img src='http://192.168.1.42/helpdesk/assets/images/logo.gif' alt=''></img>	<h2>Help Desk</h2>
											<br>Novo chamado #".$chamadoID."
											<br>
											<br>Seu chamado foi criado e esta aguardando o atendimento de um tecnico.
											<br>
											<br>Acesse seu chamado clicando <a href='http://192.168.1.42/helpdesk/controller/user/acchamado.php?t=".$chamadoToken."'>aqui</a>.
											<br>
											<br>Este email não deve ser respondido");
					$mailUser->AltBody = UTF8_DECODE( 
										"	Novo chamado #".$chamadoID."
											
											Seu chamado foi criado e esta aguardando o atendimento de um tecnico.
											
											Este email não deve ser respondido");

					$mailUser->Send();
				}
				if($m==1){
					$mailTec = $this->mail;
					$mailTec->clearAllRecipients();
					#	Envio à equipe de suporte	#
					
					$mailTec->AddAddress("bruno_oliveira@henriquestefani.com.br","Suporte");
					
					$mailTec->Subject = UTF8_DECODE("[HELPDESK #".$chamadoID."] Novo chamado: ".STRTOUPPER($chamadoTitulo));
					$mailTec->Body    = UTF8_DECODE(
										"		<img src='http://192.168.1.42/helpdesk/assets/images/logo.gif' alt=''></img>	<h2>Help Desk</h2>
											<br>Novo chamado #".$chamadoID."
											<br>
												<table width='100%'>
													<tr>
														<td colspan='3'><h3><strong>".$chamadoTitulo."</strong></h3></td>
														<td><strong>Solicitante: </strong>".$chamadoNome."
													</tr>
													<tr>
														<td width='25%'><strong>Tipo: </strong>".$chamadoTipo."</td>
														<td width='25%'><strong>Area: </strong>".$chamadoArea."</td>
														<td width='25%'><strong>Categoria: </strong>".$chamadoCategoria."</td>
														<td width='25%'><strong>Prioridade: </strong>".$chamadoPrioridade."</td>
													</tr>
													<tr>
														<td colspan='4'><strong>Descricao: </strong>".$chamadoDescricao."</td>
													</tr>
												</table>
											<br>
											<br>Para mais informações, acesse seu chamado no Helpdesk clicando <a href='http://192.168.1.42/helpdesk/controller/tech/acchamado.php?t=".$chamadoToken."'>aqui</a>.
											<br>
											<br>Este email não deve ser respondido");
					$mailTec->AltBody = UTF8_DECODE(
										"	Novo chamado #".$chamadoID."
											
											".$chamadoTitulo."
											
											Tipo: ".$chamadoTipo."
											Area: ".$chamadoArea."
											Categoria: ".$chamadoCategoria."
											Prioridade: ".$chamadoPrioridade."
											
											Descricao: ".$chamadoDescricao."
											
											Este email não deve ser respondido");
											
					$mailTec->Send();
				}
			}
		}
		
	}