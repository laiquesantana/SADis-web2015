<html>
<head>
  <meta charset="utf-8" />
  <title>SADis - Envio Concluído</title>
  <link rel="stylesheet" href="css/960_24_col.css" type='text/css'/> <!-- Grid 960 -->
  <link rel="stylesheet" href="css/jquery.dataTables.css" type='text/css'/> <!-- Grid 960 -->
  <link rel="stylesheet" href="css/style.css" type='text/css' /> 
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'><!-- GoogleFonts -->
</head>
<?php 
  require_once("db.php");

  $codigo = strip_tags(mysql_real_escape_string($_POST['codigo'],$con));
  $nome = strip_tags(mysql_real_escape_string($_POST['nome'],$con));
  $email = strip_tags(mysql_real_escape_string($_POST['email'],$con));
  $matricula = strip_tags(mysql_real_escape_string($_POST['matricula'],$con));
  $telefone = strip_tags(mysql_real_escape_string($_POST["telefone"],$con));
  $nmFaculdade = strip_tags(mysql_real_escape_string($_POST["faculdade"],$con));
  $idFaculdade = strip_tags(mysql_real_escape_string($_POST["idFaculdade"],$con));
  $idCurso = strip_tags(mysql_real_escape_string($_POST["curso"],$con));
  $status = "Em andamento";
  //$comments = $_POST["comments"];

  // consulta os dados através dos indices do curso
  $result = mysql_query("SELECT * from faculdades where CdIdeFacul  = '".$idFaculdade."';");
  // Se a faculdade nao tiver sido cadastrada ainda, cadastre-a
  if (mysql_num_rows($result) == 0){
    $sql = "INSERT INTO  faculdades 
                    (
                    CdIdeFacul,
                    NmIdeFacul
                    )
                    VALUES
                    ( 
                    '" . $idFaculdade . "' ,
                    '" . $nmFaculdade . "'
                    );";
    
    $rs = mysql_query($sql);
  }

  // Se a insercao nao e repetida, insira a solicitacao no banco de dados
  $result = mysql_query("SELECT * FROM solicitacoes WHERE NmIdeAluno='".$nome."' AND 
  	CURSOS_CdIdeCurso='".$idCurso."' AND 
  	FACULDADES_CdIdeFacul='".$idFaculdade."' AND
    TelIdeAluno='".$telefone."' AND
    EmailIdeAluno='".$email."' AND
    MatIdeAluno='".$matricula."' AND
    StatusSolic='".$status."' AND
    CodSolic='".$codigo."';");
  if (mysql_num_rows($result) == 0){
    $sql = "INSERT INTO  solicitacoes 
                    (
                    NmIdeAluno,
                    CURSOS_CdIdeCurso,
                    FACULDADES_CdIdeFacul,
                    TelIdeAluno,
                    EmailIdeAluno,
                    MatIdeAluno,
                    StatusSolic,
                    CodSolic                      
                    )
                    VALUES
                    ( 
                    '" . $nome . "' ,
                    '" . $idCurso . "' ,
                    '" . $idFaculdade . "' ,
                    '" . $telefone . "' ,
                    '" . $email . "' ,
                    '" . $matricula . "' ,
                    '" . $status . "' ,
                    '" . $codigo . "'
                    );";
                        
    $rs = mysql_query($sql);  
    
    // Pega o indice do ultimo insert para gravar na tabela relacionamento
    $indAluno = mysql_insert_id();

    include ("gravarDisciplinas.php");

    // Salvar anexos
    $uploadtemp = 'admin/solicitacoes/uploads_temp/';
    $uploaddir = 'admin/solicitacoes/uploads/';

    if (isset($_POST["files"])){
      $files = unserialize($_POST["files"]);

      foreach ($files as $file){
        if (file_exists($uploadtemp.$file)){
          //move_uploaded_file($uploadtemp.$file, $uploaddir.$file); // Nao funciona
          copy($uploadtemp.$file, $uploaddir.$file);
          unlink($uploadtemp.$file);
        }
      }
    }  
  }
?>


<body>
  <div class="background">
    <div class="container_24">
      <div class="grid_4 suffix_13">
        <div class="logo">
          <a href="index.html"><img src="logo_SADis_menor.png"></a>
        </div>
      </div>

      <div class="grid_24">
        <div class="background_transparente">    
          <div class="id_aba_ativa">
            Envio Concluído
          </div>

          <div class="clearfix"></div> 
          <div class="background_conteudo">
            <h2> Solicitação enviada com sucesso!</h2></br>
            <h2> Código da Solicitação para acompanhamento: <?php echo $codigo;?></h2></br>
<?php 
  
	$faculdadeQuery = mysql_query("SELECT faculdades.NmIdeFacul FROM solicitacoes JOIN faculdades ON solicitacoes.FACULDADES_CdIdeFacul = faculdades.CdIdeFacul WHERE solicitacoes.CodSolic='".$codigo."';");
	$faculdadeRetornada = mysql_fetch_row($faculdadeQuery);
	$faculdade = $faculdadeRetornada[0];
	
	
	$cursos = mysql_query("SELECT cursos.NmIdeCur FROM cursos JOIN solicitacoes ON solicitacoes.CURSOS_CdIdeCurso = cursos.CdIdeCur WHERE solicitacoes.CodSolic='".$codigo."';");
	$cursoRetornado = mysql_fetch_row($cursos);
	$curso = $cursoRetornado[0];
	
	
	$disciplinasEncontradas = mysql_query("SELECT r_alunos_disciplinas.CdIdeDisAlu FROM r_alunos_disciplinas JOIN solicitacoes ON solicitacoes.CdIdeAluno = r_alunos_disciplinas.ALUNOS_CdIdeAlu WHERE solicitacoes.CodSolic='".$codigo."';");
	$disciplinasNovas = mysql_fetch_row($disciplinasEncontradas);
	$disciplina = $disciplinasNovas[0];
  
	$mensagem = "<html>
	<table width='510' border='1' cellpadding='1' cellspacing='1' bgcolor='#CCCCCC'>
			    <tr>
                     <td width='500'>Foi aberta uma solicitação de aproveitamento de disciplinas em seu colegiado </td>
              </tr>
             
              	 <tr>
                      <td width='320'>Nome:$nome</td>
    	         </tr>
             
              <tr>
                      <td width='320'>Matricula:$matricula</td>
    	        </tr>
                
                 <tr>
                      <td width='320'>Telefone:$telefone</td>
    	        </tr>
                
                 <tr>
                      <td width='320'>Email:$email</td>
    	        </tr>
    				
                <tr>
                      <td width='320'>Código de Acompanhamento:<b>$codigo</b></td>
              </tr>
               
               <tr>
                      <td width='320'>Faculdade de Origem:$faculdade</td>
    	       </tr>
               
               <tr>
                      <td width='320'>Curso Solicitado:$curso</td>
    	       </tr>
  </table>
</html>
	";
	
  $emailenviar = $email;
  $destino = $email;
  $assunto = "Abertura de solicitação de aproveitamento de disciplina";

  // É necessário indicar que o formato do e-mail é html
  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  $headers .= 'From: Sadis<Sadis@sadisweb.br>';

  $enviaremail = mail($destino, $assunto, $mensagem, $headers);
  if($enviaremail){
    echo "<h2>Um email foi enviado com o número da solicitação para acompanhamento do processo.</h2>";
  } else {
    echo "<h2>Houve uma falha no envio do email de confirmação</h2>";
  }
  ?>

            <a href="index.html"><button class="butn butn-rc butn-shadow butn-primary">Retornar a página principal</button></a>


            <div class="clearfix"></div>
          </div>
        </div>
      </div>
    </div>  
  </div>           
</body>


</html>
