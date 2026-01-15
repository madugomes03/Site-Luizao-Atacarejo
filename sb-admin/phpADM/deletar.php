<?php 
session_start();

// Verifica se o usu«¡rio est«¡ logado 
if(!isset($_SESSION['usuario_logado']) && !isset($_SESSION['logado'])) {
    header("Location: ../index.html"); 
    exit;
}

// Realizando a conex«ªo com o banco
require 'configuracao.php'; 
require 'conexao.php';
$link = DB_connect();

// For«®a o ID a ser um n«âmero inteiro. Se vier texto ou c«Ñdigo malicioso, vira 0.
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    
    // Em vez de colocar o ID direto, usamos o interroga«®«ªo (?)
    $query = "DELETE FROM produtos WHERE id = ?";
    $stmt = mysqli_prepare($link, $query);

    if ($stmt) {
        
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Produto deletado com sucesso!'); window.location.href = '../utilities-animation.php';</script>";
        } else {
            
            echo "<script>alert('Falha ao deletar o produto! Tente novamente.'); window.location.href = '../utilities-animation.php';</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Erro interno ao preparar exclus«ªo.'); window.location.href = '../utilities-animation.php';</script>";
    }
} else {
    echo "<script>alert('ID de produto inv«¡lido.'); window.location.href = '../utilities-animation.php';</script>";
}

// Fecha Conex«ªo	
DB_Close($link);
?>