<?php 
	    //Realizando a conexão com o banco
	    require '../phpADM/configuracao.php'; 
	    require '../phpADM/conexao.php';
	    $link = DB_connect();

	    //Recebe 
	    $id = $_GET['id'];

		//Consulta SQL de inserção:
		$query = "DELETE FROM ofertas WHERE id_promo = '$id'"; 
		$result = @mysqli_query($link, $query) or die(mysqli_connect_error($link));

        if ($result) {
            echo "<script>alert('Oferta deletada!'); window.location.href = '../utilities-other.php';</script>";
        } else {
            echo "<script>alert('Falha ao deletar a oferta! Tente novamente.'); window.location.href = '../utilities-other.php';</script>";
        }
	    //Fecha Conexão	
	    DB_Close($link);
?>