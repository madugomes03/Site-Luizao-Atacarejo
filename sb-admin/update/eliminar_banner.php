<?php 
	    //Realizando a conexão com o banco
	    require '../phpADM/configuracao.php'; 
	    require '../phpADM/conexao.php';
	    $link = DB_connect();

	    //Recebe 
	    $id = $_GET['id'];

		//Consulta SQL de inserção:
		$query = "DELETE FROM banner WHERE id_banner = '$id'"; 
		$result = @mysqli_query($link, $query) or die(mysqli_connect_error($link));

        if ($result) {
            echo "<script>alert('Banner deletado!'); window.location.href = '../cards.php';</script>";
        } else {
            echo "<script>alert('Falha ao deletar! Tente novamente.'); window.location.href = '../cards.php';</script>";
        }
	    //Fecha Conexão	
	    DB_Close($link);
?>