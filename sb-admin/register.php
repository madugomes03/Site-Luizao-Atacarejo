<?php
session_start();
if (!isset($_SESSION["logado"]) || $_SESSION["logado"] !== TRUE) {
    echo "<script>alert('Acesso negado! Faça seu login.'); window.location.href = 'index.html';</script>";
    exit;
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

    <title>Registro - Painel dos produtos</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Criar uma conta</h1>
                            </div>
                            <form action="phpADM/cadastroADM.php" method="post" class="user">
                                <div class="form-group row">
                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                        <input name="nome" type="text" class="form-control form-control-user" id="exampleFirstName"
                                            placeholder="Nome Completo">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input name="usuario" type="text" class="form-control form-control-user" id="exampleInputNameUser"
                                        placeholder="Nome de usuário">
                                </div>

                                <div class="form-group">
                                    <input name="email" type="text" class="form-control form-control-user" id="exampleInputEmail"
                                        placeholder="Endereço de e-mail">
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0 position-relative">
                                        <input name="senha" type="password" class="form-control form-control-user" id="senha1"
                                            placeholder="Senha">
                                        <span class="position-absolute" style="top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;" onclick="toggleSenha('senha1', this)">
                                            <i class="fa-solid fa-eye" id="iconeSenha1"></i>
                                        </span>
                                    </div>

                                    <div class="col-sm-6 position-relative">
                                        <input name="senhaR" type="password" class="form-control form-control-user" id="senha2"
                                            placeholder="Repita a senha">
                                        <span class="position-absolute" style="top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;" onclick="toggleSenha('senha2', this)">
                                            <i class="fa-solid fa-eye" id="iconeSenha2"></i>
                                        </span>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Registrar conta
                                </button>
                            </form>
                            
                            <script>
                                function toggleSenha(idCampo, elementoSpan) {
                                    const campo = document.getElementById(idCampo);
                                    const icone = elementoSpan.querySelector("i");

                                    if (campo.type === "password") {
                                        campo.type = "text";
                                        icone.classList.remove("fa-eye");
                                        icone.classList.add("fa-eye-slash");
                                    } else {
                                        campo.type = "password";
                                        icone.classList.remove("fa-eye-slash");
                                        icone.classList.add("fa-eye");
                                    }
                                }
                            </script>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>