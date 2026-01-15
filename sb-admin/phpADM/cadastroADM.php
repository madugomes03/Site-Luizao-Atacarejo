<?php 
    require 'configuracao.php'; 
    require 'conexao.php';

    $link = DB_connect();

    // Recebe dados do formulário
    $nome = $_POST['nome'];
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];
    $senhaR = $_POST['senhaR'];

    if ($senha === $senhaR) {

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare($link, "INSERT INTO admin (nome, usuario, senha) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $nome, $usuario, $senhaHash);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            echo "<script>window.location.href = '../painel.php';</script>";
        } else {
            echo "<script>alert('Ocorreu algum erro ao registrar.');</script>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<script>";
        echo "alert('As senhas não coincidem.');";
        echo "window.location.href = '../register.php';";
        echo "</script>";
    }

    DB_Close($link); 	
?>
