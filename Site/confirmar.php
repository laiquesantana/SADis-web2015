<html>
<head>
  <meta charset="utf-8" />
  <title>SADis - Confirmar Aproveitamento</title>
  <link rel="stylesheet" href="css/960_24_col.css" type='text/css'/> <!-- Grid 960 -->
  <link rel="stylesheet" href="css/jquery.dataTables.css" type='text/css'/> <!-- Grid 960 -->
  <link rel="stylesheet" href="css/style.css" type='text/css' /> 
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'><!-- GoogleFonts -->
</head>
<?php 
  require_once("db.php");
  error_reporting(0);

  // If there's a not set field
  if (!(isset($_POST["Nome"])&&
    isset($_POST["Telefone"])&&
    isset($_POST["Email"])&&
    isset($_POST["Matricula"])&&
    isset($_POST["Faculdade"])&&
    isset($_POST["CURSO"])&&
    isset($_POST["num_files"])
    )){
    // TODO throw error
    header('Location: index.html');
    die();
  }

  // Create code
  $result = mysql_query("SELECT MAX(CdIdeDisAlu) FROM r_alunos_disciplinas;");
  if (mysql_num_rows($result) == 0) $codigo = sprintf("%08d", 1);
  else{
    $row = mysql_fetch_array($result);
    $codigo = sprintf("%08d", ((int)$row[0])+1);
  }

  //Tratamento SQL-Injection
  $nome = $_POST["Nome"];
  $telefone = $_POST["Telefone"];
  $email = $_POST["Email"];
  $matricula = $_POST["Matricula"];
  $nmFaculdade = $_POST["Faculdade"];
  $IdFaculdade = $_POST["idFaculdade"];
  $IdCurso = $_POST["CURSO"];
  $numDisc = $_POST["num_files"];
  
  $nome = strip_tags(mysql_real_escape_string($nome,$con));  
  $telefone = strip_tags(mysql_real_escape_string($telefone,$con));  
  $email = strip_tags(mysql_real_escape_string($email,$con));  
  $matricula = strip_tags(mysql_real_escape_string($matricula,$con));  
  $nmFaculdade = strip_tags(mysql_real_escape_string($nmFaculdade,$con));  
  $IdCurso = strip_tags(mysql_real_escape_string($IdCurso,$con));  
  $IdFaculdade = strip_tags(mysql_real_escape_string($IdFaculdade,$con));  

  //Imagem 
  $uploaddir = 'admin/solicitacoes/uploads_temp/';
  $erro = $config = $files = array();

  // Tamanho máximo do arquivo (em bytes)
  $config["tamanho"] = 1006883;
  // Largura máxima (pixels)
  $config["largura"] = 2000;
  // Altura máxima (pixels)
  $config["altura"]  = 2000;
  
  // Formulário postado... executa as ações
  for ($i = 1; $i <= $numDisc; $i++){
    if (isset($_FILES["userfile".$i])){
      $arquivo = $_FILES["userfile".$i];

      // Verifica se o mime-type do arquivo é de imagem
      if(!eregi("^image\/(pjpeg|jpeg|png|gif|bmp)$", $arquivo["type"])){
        $erro[] = "Arquivo em formato inválido! A imagem deve ser jpg, jpeg, 
          bmp, gif, png ou pdf. Envie outro arquivo";
      }
      else{
        // Verifica tamanho do arquivo
        if($arquivo["size"] > $config["tamanho"]){
          $erro[] = "Arquivo em tamanho muito grande! 
            A imagem deve ser de no máximo " . $config["tamanho"] . " bytes. 
            Envie outro arquivo";
        }
      
        // Para verificar as dimensões da imagem
        $tamanhos = getimagesize($arquivo["tmp_name"]);
      
        // Verifica largura
        if($tamanhos[0] > $config["largura"]){
          $erro[] = "A largura da imagem não deve 
            ultrapassar " . $config["largura"] . " pixels";
        }

        // Verifica altura
        if($tamanhos[1] > $config["altura"]){
          $erro[] = "A altura da imagem não deve 
            ultrapassar " . $config["altura"] . " pixels";
        }
      }
    
      if ( empty($erro) && isset($arquivo["tmp_name"]) ){
        // Pega extensão do arquivo
        preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $arquivo["name"], $ext);

        // Gera um nome único para a imagem
        $imagem_nome = $codigo."_".$i.".". $ext[1];

        // Caminho de onde a imagem ficará
        $imagem_dir = $uploaddir . $imagem_nome;
        
        if (move_uploaded_file($arquivo["tmp_name"], $imagem_dir)) 
          array_push($files, $imagem_nome);
      }
    }
  }

  // consulta os dados através dos indices do curso
  $result = mysql_query("SELECT CdIdeCur, NmIdeCur from cursos where CdIdeCur  = '".$IdCurso."';");
  while($row = mysql_fetch_array($result)){
    $nmCurso = utf8_encode($row["NmIdeCur"]);
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
            Confirmar Solicitação
          </div>

          <div class="clearfix"></div> 
          <div class="background_conteudo">
            <form method="POST" action="gerar.php">
              
              <h2>Nome Completo  </h2> <?php echo $nome;?>
              <h2>Telefone </h2>  <?php echo $telefone;?>
              <h2>Email </h2>  <?php echo $email;?>
              <h2>Matrícula </h2> <?php echo $matricula;?>
              <h2>Faculdade de Origem </h2>  <?php echo $nmFaculdade;?>
              <h2>Curso Solicitado </h2> <?php echo $nmCurso;?>
              
              <input type="hidden" name="nome" value="<?php echo $nome;?>">
              <input type="hidden" name="telefone" value="<?php echo $telefone;?>">
              <input type="hidden" name="email" value="<?php echo $email;?>">
              <input type="hidden" name="matricula" value="<?php echo $matricula;?>">
              <input type="hidden" name="faculdade" value="<?php echo $nmFaculdade;?>">
              <input type="hidden" name="idFaculdade" value="<?php echo $idFaculdade;?>">
              <input type="hidden" name="curso" value="<?php echo $IdCurso;?>">
              <?php for ($i = 1; $i <= $numDisc; $i += 1){ ?>
                <input type="hidden" name="nomeDisciplina<?php echo $i;?>" value='<?php echo $_POST['nomeDisciplina'.$i]; ?>'>
                <input type="hidden" name="codigoDisciplina<?php echo $i;?>" value='<?php echo $_POST['codigoDisciplina'.$i]; ?>'>
                <input type="hidden" name="cargaHorariaDisciplina<?php echo $i;?>" value='<?php echo $_POST['cargaHorariaDisciplina'.$i]; ?>'>
                <input type="hidden" name="comentarioDisciplina<?php echo $i;?>" value='<?php echo $_POST['comentarioDisciplina'.$i]; ?>'>
              <?php } ?>
              <input type="hidden" name="num_files" value="<?php echo $numDisc;?>">
              <input type="hidden" name="codigo" value="<?php echo $codigo;?>">
              <input type="hidden" name="files" value='<?php echo serialize($files);?>'>

              <br>
              <br>
              <button style="margin-left:160px;"class="but but-rc but-shadow but-primary" type="submit">Confirmar</button>
            </form>

            <form method="POST" action="removeTempFiles.php">
              <input type="hidden" name="files" value='<?php echo serialize($files);?>'>
              <button style="margin-top:-48px;" class="but but-rc but-shadow but-primary">Cancelar</button>
            </form>

            <div class="clearfix"></div>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
    </div>  
  </div>           
</body>



</html>
