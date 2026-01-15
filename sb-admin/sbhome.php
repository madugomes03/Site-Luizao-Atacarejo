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

    <title>Painel de controle</title>

    
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">


    <style>
    #content {
        background-image: url('img.home/img-admin.png');
        background-size: contain; /* Faz a imagem cobrir toda a área, sem distorcer */
        background-position: center; /* Centraliza a imagem */
        background-repeat: no-repeat; /* Impede que a imagem se repita */
    }
</style>

</head>

<body id="page-top">

    <div id="wrapper">

        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="sbhome.php">
                <div class="sidebar-brand-icon rotate-n-15">
                </div>
                <div class="sidebar-brand-text mx-3"> ADMINISTRADOR</sup></div>
            </a>

            <hr class="sidebar-divider my-0">

            <li class="nav-item active">
                <a class="nav-link" href="painel.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>CATEGORIAS</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="https://luizaoatacarejo.com.br" target="_blank">
                    <i class="fas fa-fw fa-store"></i>
                    <span>Acessar Site Principal</span></a>
            </li>

            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                Interface do Usuário
            </div>

        

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Ajuste de página</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Páginas:</h6>
                        <a class="collapse-item" href="utilities-animation.php">Produtos Cadastrados</a>
                        <a class="collapse-item" href="utilities-other.php">Ofertas em Destaque</a>
                        <a class="collapse-item" href="cards.php">Banners</a>
                    </div>
                </div>
            </li>

            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                INTERFACE ADMINISTRADOR
            </div>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Admin</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Dados Administrador</h6>
                        <a class="collapse-item" href="" data-toggle="modal" data-target="#perfilModal">Perfil</a>
                        <a class="collapse-item"  href="" data-toggle="modal" data-target="#senhaModal">Registrar outro ADM</a>
                        <div class="collapse-divider"></div>
                        
                    </div>
                </div>
            </li>

            <div class="modal fade" id="perfilModal" tabindex="-1" role="dialog" aria-labelledby="perfilModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    
                    <?php
                        // Conexão com o banco
                        require_once 'phpADM/configuracao.php'; 
                        require_once 'phpADM/conexao.php';
                        
                        if(!isset($link) || !$link) {
                            $link = DB_connect();
                        }

                        // Busca ID correto
                        $id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 0;

                        // Consulta SQL direta
                        $query = "SELECT * FROM admin WHERE id = $id_usuario"; 
                        $result = @mysqli_query($link, $query);
                        $registro = mysqli_fetch_assoc($result);
                    ?>

                    <div class="modal-header">
                        <h5 class="modal-title" id="perfilModalLabel">Informações do Perfil</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <?php if($registro) { ?>
                            <p><strong>Nome: </strong><?php echo $registro["nome"];?></p>
                            <p><strong>Usuário: </strong><?php echo $registro["usuario"];?></p>
                            <p><strong>Email: </strong><?php echo $registro["email"];?></p>
                        <?php } else { ?>
                            <p class="text-danger">Erro: Perfil não encontrado.</p>
                        <?php } ?>
                        
                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="forgot-password.php" class="btn btn-primary">Mudar Senha</a>
                            <a href="phpADM/logout.php" class="btn btn-danger">Sair</a>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="senhaModal" tabindex="-1" role="dialog" aria-labelledby="senhaModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form action="phpADM/verificar_senha.php" method="POST" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="senhaModalLabel">Confirmação de Senha</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="senha">Digite a senha de acesso:</label>
                        <input type="password" class="form-control" name="senha" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Confirmar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                    </form>
                </div>
            </div>

            <hr class="sidebar-divider d-none d-md-block">

            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

        
                    <ul class="navbar-nav ml-auto">

                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                
                        </li>

                    </ul>

                </nav>

                <div class="container-fluid">


                    <div class="row">


                       

                        <div class="col-xl-12 col-lg-4">

                            </div>
                </div>
                
            </div>
          
        </div>
        
        
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <script src="js/sb-admin-2.min.js"></script>

    <script src="vendor/chart.js/Chart.min.js"></script>

    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>