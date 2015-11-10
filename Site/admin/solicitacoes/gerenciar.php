<head>
<meta charset="utf-8" />
<title></title>
<link rel="stylesheet" href="../../css/960_24_col.css" type='text/css'/> <!-- Grid 960 -->
<link rel="stylesheet" href="../../css/jquery.dataTables.css" type='text/css'/> <!-- Grid 960 -->
<link rel="stylesheet" href="../../css/style.css" type='text/css' /> 
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'><!-- GoogleFonts -->
<script src="../../js/jquery-1.10.2.min.js"></script>
<script src="../../js/jquery.dataTables.js"></script>

</head>
<?php
	// Nome: cadastro.php
	// Cadastra um novo curso
	session_start();
	if (!isset($_SESSION["acesso"])){
		header("location: ..\login.php");
	
	}		
	//auditoria starts
	require "../db.php";
	$rotina = "AcessarSolicitacoes";
	$nivel = $_SESSION["nivel"];
	$usuario = $_SESSION["usuario"];
	$query = "SELECT count(*) FROM direitos WHERE DsIdeRotina = '$rotina' AND Niveis_CdIdeNivel = '$nivel' AND AltIdeRot = 1";
	$rs = mysql_query($query);
	$row = mysql_fetch_row($rs);
	
	if ( $row[0] > 0 ) { // nivel pode ver a pagina
		$query = "SELECT CdIdeUsu from usuarios where NmIdeUsu = '$usuario'";
		$rs = mysql_query($query);
		$row = mysql_fetch_row($rs);
		$idUsuario = $row[0];
		$rotina = "AcessarSolicitacoes->Gerenciar->".$_GET['CdIdeAluno'];
		require "../pegarIp.php";
		$query = "INSERT into auditoria ( Niveis_CdIdeNivel, Usuarios_CdIdeUsu, DsIdeAudit , IpAudit ) 
					VALUES ( '$nivel' , '$idUsuario', '$rotina' , '$ip' ) ";					
		$rs = mysql_query($query);
	//auditoria ends
	

		$id = $_GET['CdIdeAluno'];
		$id = strip_tags(mysql_real_escape_string($id,$con));
		$sql = "SELECT * FROM solicitacoes WHERE CdIdeAluno = ".$id;
		$result   = mysql_query($sql); 
		
		while($row = mysql_fetch_array($result)){
			
			$idFacul = utf8_encode($row["FACULDADES_CdIdeFacul"]);
			$result1 = mysql_query("SELECT CdIdeFacul, NmIdeFacul from faculdades where CdIdeFacul = '".$idFacul."';");
			while($row1 = mysql_fetch_array($result1)){
				$nmFaculdade = utf8_encode($row1["NmIdeFacul"]);
			}
			
			$iDcurso = utf8_encode($row["CURSOS_CdIdeCurso"]);
			$result2 = mysql_query("SELECT CdIdeCur, NmIdeCur from cursos where CdIdeCur  = ".$iDcurso."");
			while($row2 = mysql_fetch_array($result2)){
				$nmCurso = utf8_encode($row2["NmIdeCur"]);
			}
			
			$disc2 ="";
			$disc2 .= "";
			$aluno = utf8_encode($row["CdIdeAluno"]);
			$sql1 = "SELECT * FROM r_alunos_disciplinas WHERE ALUNOS_CdIdeAlu = ".$aluno;
			$result3 = mysql_query($sql1);
			while($row3 = mysql_fetch_array($result3))
			{ 
 
				$disc2 .= utf8_encode($row3['NmIdeDisciplina']).", ".utf8_encode($row3['CargaHorariaDisciplina'])." horas<br>";

			}
			
			$nome = utf8_encode($row["NmIdeAluno"]);
			$telefone = utf8_encode($row["TelIdeAluno"]);
			$email = utf8_encode($row["EmailIdeAluno"]);
			$matricula = utf8_encode($row["MatIdeAluno"]);
			$status = utf8_encode($row["StatusSolic"]);
			$codigo = utf8_encode($row["CodSolic"]);

			$files = scandir("uploads");
			$comprovantes = array();
			foreach($files as $file){
				if (preg_match("/".$row["CodSolic"]."_[0-9]*\.\w+/", $file, $match)) $comprovantes[] = $match[0];
			}
		}
		
		

?>
<body>
	<div class="background">
		<div class="container_24">
			<div class="grid_4 suffix_13">
				<div class="logo">
					<a href="../index.php"><img src="../../img/logo_SADis_menor.png"></a>
				</div>
			</div>
			<div class="grid_7">
				<div class="id_usuario">
					<a style="margin-left:15px;" href="login.php" class="right"title="Sair"><input name="exit1" type="button" class="exit1"></a>
					
				</div>
			</div>
			<div class="grid_24">
				<div class="background_transparente">    
					<div class="id_aba_ativa">
						Gerenciar Solicitação
					</div>
					<div class="id_aba">
						<a href="index.php">Solicitações</a>
					</div>

					<div class="clearfix"></div> 
					<div class="background_conteudo">

							
						<h2>Nome Completo  </h2> <?php echo $nome;?><br><br>
						<h2>Telefone </h2>  <?php echo $telefone;?><br><br>
						<h2>Email </h2>  <?php echo $email;?><br><br>
						<h2>Matrícula </h2> <?php echo $matricula;?><br><br>
						<h2>Faculdade Atual </h2>  <?php echo $nmFaculdade;?><br><br>
						<h2>Curso Solicitado </h2> <?php echo $nmCurso;?><br><br>
						<h2>Disciplinas Cursadas</h2>  <?php echo $disc2;?><br><br>
						<h2>Comprovantes</h2>  
						<?php //echo $comprovante;
						  foreach ($comprovantes as $comprovante)
							echo "<a href=uploads/".$comprovante."><font color=D2691E>".$comprovante."</font></a><br>";
						?>
						<br><br>
						<h2>Status</h2>  <?php echo $status;?><br><br>
						<h2>Código da Solicitação</h2>  <?php echo $codigo;?><br><br>
						
						<br><br><br><br>
						<br><br>
						<a href="alterar.php?CdIdeAluno=<?php echo $id;?>"><button style="margin-top:-62px;" class="but but-rc but-shadow but-primary"  >Alterar Status</button></a>

						<div class="clearfix"></div>
					</div>
				</div>
			</div>
		</div>  
</div>  
</body>

<?php }

else {
header("location: ../acessonegado.php");
}
?>
