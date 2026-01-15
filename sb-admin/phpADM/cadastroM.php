<?php 
    //Realizando a conexão com o banco 
    require 'conexao.php';
    require 'configuracao.php';
    $link = DB_connect();

    //Recebe dados
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $subcategoria = $_POST['subcategoria'];
    $preco = $_POST['preco'];
    $status = $_POST['status'];

    if(isset($_FILES['imagem'])){
        $imagem = $_FILES['imagem'];

        // VERIFICAÇÃO DE ERRO DE UPLOAD
        if($imagem['error']) {
            echo "<script>alert('Falha ao enviar imagem! Tente novamente.'); window.location.href = '../formularios/merceariaF.html';</script>";
            die();
        }

        // VERIFICAÇÃO DE TAMANHO (2MB)
        if ($imagem['size'] > 2097152) {
            echo "<script>alert('Arquivo muito grande! Tamanho máximo 2MB! Tente novamente.'); window.location.href = '../formularios/merceariaF.html';</script>";
            die();
        }
        
        $pasta = "arquivos/"; 
        $name = $imagem['name'];
        $nomeNovo = uniqid();
        $extensao = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        
     
        if($extensao != "jpg" && $extensao != 'png' && $extensao !='jpeg') {
            echo "<script>alert('Tipo de arquivo não aceito! Tente novamente.'); window.location.href = '../formularios/merceariaF.html';</script>";
            die();
        }

        $path = $pasta . $nomeNovo . "." . $extensao;

    
        $certo = move_uploaded_file($imagem["tmp_name"], $path);
        
        if($certo){
            //Consulta SQL de inserção:
            $query = "INSERT INTO produtos (nome, descricao, categoria, subcategoria, preco, status, path) VALUES ('$nome', '$descricao', 'Mercearia', '$subcategoria', '$preco', '$status', '$path')";
            $result = @mysqli_query($link, $query) or die (mysqli_connect_error($link));

            if($result){
                
    
                echo "<script>alert('Produto cadastrado com sucesso!'); window.location.href = '../formularios/merceariaF.html';</script>";
            } else {
                echo "<script>alert('Falha ao cadastrar! Tente novamente.'); window.location.href = '../formularios/merceariaF.html';</script>";
            }
    
        } else {
            echo "<script>alert('Falha ao salvar o arquivo na pasta! Verifique as permissões.'); window.location.href = '../formularios/merceariaF.html';</script>";
        }
    }

    //Fecha Conexão 
    DB_Close($link);        
?>