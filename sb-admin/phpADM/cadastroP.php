<?php
session_start();

if(!isset($_SESSION['usuario_logado']) && !isset($_SESSION['logado'])) {
    header("Location: ../index.html");
    exit;
}

require 'configuracao.php';
require 'conexao.php';

$link = DB_connect();


$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$categoria = 'Padaria'; // Fixo, pois o arquivo é cadastroP (Padaria)
$subcategoria = $_POST['subcategoria'];
$preco = $_POST['preco']; // Ex: 10,00
$status = $_POST['status'];


$caminho_final = "arquivos/sem_imagem.png"; // Imagem padrão caso falhe

if(isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0){
    $extensao = strtolower(substr($_FILES['imagem']['name'], -4)); // .jpg, .png
    $novo_nome = md5(time()) . $extensao; // Nome único criptografado
    $diretorio = "arquivos/"; 
    
    // Tenta mover a imagem para a pasta
    if(move_uploaded_file($_FILES['imagem']['tmp_name'], $diretorio . $novo_nome)){
        $caminho_final = $diretorio . $novo_nome;
    }
}


$sql = "INSERT INTO produtos (nome, descricao, categoria, subcategoria, preco, status, path) VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($link, $sql);

if($stmt) {
    // "sssssss" significa que são 7 strings
    mysqli_stmt_bind_param($stmt, "sssssss", $nome, $descricao, $categoria, $subcategoria, $preco, $status, $caminho_final);
    
    if(mysqli_stmt_execute($stmt)) {
        // Sucesso
        echo "<script>alert('Produto cadastrado com sucesso!'); window.location.href = '../formularios/padariaF.html';</script>";
    } else {
        // Erro ao salvar
        echo "<script>alert('Erro ao salvar no banco!'); window.history.back();</script>";
    }
    mysqli_stmt_close($stmt);
} else {
    echo "Erro interno no banco de dados.";
}

DB_Close($link);
?>