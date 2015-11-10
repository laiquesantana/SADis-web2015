<?php
	$uploaddir = 'admin/solicitacoes/uploads_temp/';

  if (isset($_POST["files"])){
    $files = unserialize($_POST["files"]);

    // Only delete files if they are in the uploads folder
    foreach ($files as $file) {
      if (preg_match("/\w\.\w/i", $file)){
        if (file_exists($file)) unlink($uploaddir.$file);
      }
    }
  }
  
  header("location: solicitacao.php");
?>
