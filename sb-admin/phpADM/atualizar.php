<?php
session_start();

// 1. Verifica login (Segurança)
if(!isset($_SESSION['usuario_logado']) && !isset($_SESSION['logado'])) {
    header("Location: ../index.html");
    exit;
}

require 'configuracao.php';
require 'conexao.php';

$link = DB_connect();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$categoria_url = isset($_GET['categoria']) ? $_GET['categoria'] : '';

$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$subcategoria = $_POST['subcategoria'];
$preco = $_POST['preco'];
$status = $_POST['status'];


$sql_imagem = ""; 
$param_types = "sssssi"; 
$params = array($nome, $descricao, $subcategoria, $preco, $status, $id);


if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
    $extensao = strtolower(substr($_FILES['imagem']['name'], -4)); 
    $novo_nome = md5(time()) . $extensao; 
    $diretorio = "arquivos/"; 

   
    if(move_uploaded_file($_FILES['imagem']['tmp_name'], $diretorio . $novo_nome)){
        
        $sql_imagem = ", path = ?";
        
        $caminho_final = "arquivos/" . $novo_nome;
        
       
        $param_types = "ssssssi";
        $params = array($nome, $descricao, $subcategoria, $preco, $status, $caminho_final, $id);
    }
}

// 5. Prepara o UPDATE seguro
$query = "UPDATE produtos SET nome=?, descricao=?, subcategoria=?, preco=?, status=? $sql_imagem WHERE id=?";

$stmt = mysqli_prepare($link, $query);

if ($stmt) {
    
    mysqli_stmt_bind_param($stmt, $param_types, ...$params);
    
    if (mysqli_stmt_execute($stmt)) {
        
        echo "<script>
            alert('Produto atualizado com sucesso!');
            window.location.href = '../update/atualizar_produto.php?id=$id&categoria=$categoria_url';
        </script>";
    } else {
        echo "Erro ao atualizar no banco: " . mysqli_error($link);
    }
    mysqli_stmt_close($stmt);
} else {
    echo "Erro na preparação da query: " . mysqli_error($link);
}

DB_Close($link);
?>