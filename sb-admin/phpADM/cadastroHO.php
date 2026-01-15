<?php 
	// --- MODO DE SEGURANÇA E ERROS ---
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	require 'conexao.php';
	require 'configuracao.php';
	$link = DB_connect();

	// Verifica se os dados chegaram
	if (!isset($_POST['nome'])) {
		die("Erro: O formulário não enviou os dados. Verifique o HTML.");
	}

	$nome = $_POST['nome'];
	$descricao = $_POST['descricao'];
    $subcategoria = $_POST['subcategoria'];
    $preco = $_POST['preco'];
	$status = $_POST['status'];

	// Verifica se a imagem existe
	if(isset($_FILES['imagem']) && $_FILES['imagem']['size'] > 0){
		$imagem = $_FILES['imagem'];

		// Verificação de erros do upload
		if($imagem['error']) {
			die("<script>alert('Erro interno no upload: Código " . $imagem['error'] . "'); window.history.back();</script>");
		}

		// Verificação de tamanho (2MB)
		if ($imagem['size'] > 2097152) {
			die("<script>alert('Arquivo muito grande! Máximo 2MB.'); window.history.back();</script>");
		}
		
		$pasta = "arquivos/";
		// Cria a pasta se ela não existir
		if (!is_dir($pasta)) {
			mkdir($pasta, 0777, true); 
		}

		$name = $imagem['name'];
		$nomeNovo = uniqid();
		$extensao = strtolower(pathinfo($name, PATHINFO_EXTENSION));
		
		// Verificação de extensão
		if($extensao != "jpg" && $extensao != 'png' && $extensao !='jpeg') {
			die("<script>alert('Extensão não aceita! Use JPG ou PNG.'); window.history.back();</script>");
		}

		$path = $pasta . $nomeNovo . "." . $extensao;

		// Tenta mover a imagem
		if(move_uploaded_file($imagem["tmp_name"], $path)) {
			
			// Consulta SQL para HORTIFRUTI
			$query = "INSERT INTO produtos (nome, descricao, categoria, subcategoria, preco, status, path) VALUES ('$nome', '$descricao', 'Hortifruti', '$subcategoria', '$preco', '$status', '$path')";
			
			$result = mysqli_query($link, $query);

			if($result){
    // Redireciona de volta para o arquivo do formulário (acougueF.html)
    echo "<script>alert('Produto cadastrado com sucesso!'); window.location.href = '../formularios/hortifrutiF.html';</script>";
} else {
				echo "Erro no MySQL: " . mysqli_error($link);
			}

		} else {
			echo "Falha ao mover o arquivo para a pasta '$pasta'. Verifique as permissões.";
		}
	} else {
		// Caso esqueça de anexar a imagem
		echo "<script>alert('Nenhuma imagem foi enviada! Verifique se o formulário tem enctype=multipart/form-data'); window.history.back();</script>";
	}

	DB_Close($link); 		
?>