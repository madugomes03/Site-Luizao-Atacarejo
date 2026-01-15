<?php
    session_start();

    require 'conexao.php';
    require 'configuracao.php';

    $link = DB_connect();

    // Recebe os dados do formulário
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Prepara a consulta (Seguro contra SQL Injection)
    $stmt = mysqli_prepare($link, "SELECT id, senha FROM admin WHERE usuario = ?");
    if (!$stmt) {
        die("Erro interno no sistema."); 
    }

    // Faz o bind do parâmetro
    mysqli_stmt_bind_param($stmt, "s", $usuario);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    // Verifica se o usuário existe
    if (mysqli_stmt_num_rows($stmt) === 1) {
        mysqli_stmt_bind_result($stmt, $id, $senhaDB);
        mysqli_stmt_fetch($stmt);

        // Verifica a senha
        if (password_verify($senha, $senhaDB)) {
            
            // Regenera o ID da sessão para segurança
            session_regenerate_id(true);

            
            $_SESSION["usuario_logado"] = TRUE;  // Para os arquivos novos (seguros)
            $_SESSION["logado"] = TRUE;          // Para o sbhome.php (antigo)
            $_SESSION["id_usuario"] = $id; 
            $_SESSION["admin"] = $id; 

            header("Location: ../sbhome.php");
            exit;
        }
    }

    // Se login falhou
    echo "<script>alert('Usuário ou senha incorretos! Tente novamente.'); window.location.href = '../index.html';</script>";

    mysqli_stmt_close($stmt);
    DB_Close($link);
?>