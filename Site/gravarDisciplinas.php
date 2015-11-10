<?php  
  // Arquivo: gravarDisciplinas.php
  // Grava as disciplinas cursadas pelo aluno na tabela
  
  if (isset($_POST["num_files"]))
  for($i=1; $i <= $_POST["num_files"]; $i++){
    if (isset($_POST['nomeDisciplina'.$i])&&
        isset($_POST['codigoDisciplina'.$i])&&
        isset($_POST['cargaHorariaDisciplina'.$i])&&
        isset($_POST['comentarioDisciplina'.$i])
        ){
      $result = mysql_query("SELECT * FROM r_alunos_disciplinas WHERE 
                    ALUNOS_CdIdeAlu='".$indAluno."' AND
                    NmIdeDisciplina='".$_POST['nomeDisciplina'.$i]."' AND
                    CodDisciplina='".$_POST['codigoDisciplina'.$i]."' AND
                    CargaHorariaDisciplina='".$_POST['cargaHorariaDisciplina'.$i]."' AND
                    ComentarioDisciplina='".$_POST['comentarioDisciplina'.$i]."'
              ;");
      if (mysql_num_rows($result) == 0){
        $sql = "INSERT INTO r_alunos_disciplinas 
                    (ALUNOS_CdIdeAlu, 
                     NmIdeDisciplina, 
                     CodDisciplina, 
                     CargaHorariaDisciplina, 
                     ComentarioDisciplina
                    ) 
                    VALUES 
                    (  
                      '" . $indAluno . "' ,
                      '" . $_POST['nomeDisciplina'.$i] . "', 
                      '" . $_POST['codigoDisciplina'.$i] . "',
                      '" . $_POST['cargaHorariaDisciplina'.$i] ."',
                      '" . $_POST['comentarioDisciplina'.$i] ."'
                    );"  ;
                  
        $rs = mysql_query($sql);
      }
    }
  }
?>  
