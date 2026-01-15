<?php
session_start();

if (!isset($_SESSION["logado"]) || $_SESSION["logado"] !== TRUE) {
    echo "<script>alert('Acesso negado! Faça seu login.'); window.location.href = 'index.html';</script>";
    exit;
}

$idAdm = $_SESSION["admin"];

if (isset($_POST['trocar_senha'])) {
    require 'phpADM/configuracao.php';
    require 'phpADM/conexao.php';
    $link = DB_connect();

    $email          = mysqli_real_escape_string($link, $_POST['email']);
    $senha_atual    = $_POST['senha_atual'];
    $nova_senha     = $_POST['nova_senha'];
    $confirma_senha = $_POST['confirma_senha'];

    if ($nova_senha !== $confirma_senha) {
        echo "<script>alert('A confirmação da senha não confere.');</script>";
    } else {
        $sql    = "SELECT * FROM admin WHERE id = '$idAdm' AND email = '$email'";
        $result = mysqli_query($link, $sql);

        if ($result && mysqli_num_rows($result) == 1) {
            $dados = mysqli_fetch_assoc($result);

            if (password_verify($senha_atual, $dados['senha'])) {
                $hash   = password_hash($nova_senha, PASSWORD_DEFAULT);
                $update = "UPDATE admin SET senha = '$hash' WHERE id = '$idAdm'";

                if (mysqli_query($link, $update)) {
                    echo "<script>alert('Senha alterada com sucesso!'); window.location.href='painel.php';</script>";
                    exit;
                } else {
                    echo "<script>alert('Erro ao atualizar a senha. Tente novamente.');</script>";
                }
            } else {
                echo "<script>alert('Senha atual incorreta.');</script>";
            }
        } else {
            echo "<script>alert('Email não encontrado para este administrador.');</script>";
        }
    }

    DB_Close($link);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Painel administrador</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        /* Centralizar card verticalmente */
        .min-vh-100 {
            min-height: 100vh;
        }

        /* Limitar largura do formulário e centralizar dentro da coluna */
        .form-troca-senha {
            max-width: 380px;
            margin: 0 auto;
        }

        @media (max-width: 767.98px) {
            .card.o-hidden.border-0.shadow-lg.my-5 {
                margin-top: 2rem;
                margin-bottom: 2rem;
            }
        }

        .form-troca-senha .form-group {
            margin-bottom: 1rem;
        }

        .form-troca-senha .form-control-user {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body class="bg-gradient-primary">

<div class="container min-vh-100 d-flex align-items-center">

    <div class="row justify-content-center w-100">

        <div class="col-xl-8 col-lg-10 col-md-10">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <!-- Coluna do formulário -->
                        <div class="col-lg d-flex align-items-center">
                            <div class="p-5 w-100">
                                <div class="text-center mb-4">
                                    <h1 class="h4 text-gray-900 mb-2">Deseja mudar a sua senha?</h1>
                                    <p class="mb-0">Digite o seu email e a nova senha.</p>
                                </div>

                                <form class="user form-troca-senha" action="" method="POST">
                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-user"
                                               name="email"
                                               placeholder="Digite o seu endereço de email para verificação"
                                               required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user"
                                               name="senha_atual"
                                               placeholder="Senha atual" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user"
                                               name="nova_senha"
                                               placeholder="Nova senha" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user"
                                               name="confirma_senha"
                                               placeholder="Confirme a nova senha" required>
                                    </div>
                                    <button class="btn btn-primary btn-user btn-block" type="submit" name="trocar_senha">
                                        Trocar senha
                                    </button>
                                </form>

                            </div>
                        </div><!-- fim col-lg-6 formulário -->
                    </div><!-- fim row interna -->
                </div>
            </div>

        </div>

    </div>

</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
</body>

<style>
    /* Centralizar e limitar largura em telas menores */
    .form-troca-senha {
        max-width: 380px;
        margin: 0 auto;
    }

    /* Ajustar card para não ficar muito alto em mobile */
    @media (max-width: 767.98px) {
        .bg-password-image {
            display: none;
        }

        .card.o-hidden.border-0.shadow-lg.my-5 {
            margin-top: 2rem;
            margin-bottom: 2rem;
        }
    }

    /* Melhorar espaçamento entre campos */
    .form-troca-senha .form-group {
        margin-bottom: 1rem;
    }

    .form-troca-senha .form-control-user {
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
    }
</style>

</html>
