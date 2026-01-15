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

    <title>Painel de controle - Banners</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        /* Estilo extra para padronizar os cards */
        .card-banner-img {
            width: 100%;
            height: 150px; /* Altura fixa para alinhar os cards */
            object-fit: cover; /* Corta a imagem para preencher sem distorcer */
            border-radius: 5px;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease;
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        }

        .btn-circle-action {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }
    </style>
</head>

<body id="page-top">

    <div id="wrapper">

        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="sbhome.php">
                <div class="sidebar-brand-icon rotate-n-15">
                </div>
                <div class="sidebar-brand-text mx-3"> ADMINISTRADOR</div>
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
                Addons
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
                        <a class="collapse-item" href="" data-toggle="modal" data-target="#senhaModal">Registrar outro ADM</a>
                    </div>
                </div>
            </li>

            <div class="modal fade" id="perfilModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <?php
                        require_once 'phpADM/configuracao.php';
                        require_once 'phpADM/conexao.php';
                        if(!isset($link) || !$link) $link = DB_connect();
                        
                        // CORREÇÃO: Usando a sessão correta id_usuario
                        $id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 0; 
                        
                        $query = "SELECT * FROM admin WHERE id = $id_usuario"; 
                        $result = @mysqli_query($link, $query);
                        $registro = mysqli_fetch_assoc($result);
                        ?>
                        <div class="modal-header">
                            <h5 class="modal-title">Informações do Perfil</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?php if($registro) { ?>
                                <p><strong>Nome: </strong><?php echo $registro["nome"];?></p>
                                <p><strong>Usuário: </strong><?php echo $registro["usuario"];?></p>
                                <p><strong>Email: </strong><?php echo $registro["email"];?></p>
                            <?php } else { echo "<p class='text-danger'>Erro ao carregar perfil. Faça login novamente.</p>"; } ?>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <a href="forgot-password.php" class="btn btn-primary">Mudar Senha</a>
                                <a href="phpADM/logout.php" class="btn btn-danger">Sair</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="senhaModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form action="phpADM/verificar_senha.php" method="POST" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirmação de Senha</h5>
                            <button type="button" class="close" data-dismiss="modal">
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

                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        </ul>
                </nav>

                <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Gerenciar Banners</h1>
                        <a href="update/bannerF.html" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-plus fa-sm text-white-50"></i> Adicionar Novo Banner
                        </a>
                    </div>

                    <div class="d-block d-sm-none mb-4">
                        <a href="update/bannerF.html" class="btn btn-primary btn-block">
                            <i class="fas fa-plus"></i> Adicionar Banner
                        </a>
                    </div>

                    <div class="row">

                        <?php
                        // Reabrindo conexão para garantir (caso tenha fechado nos modais)
                        if(!$link) $link = DB_connect();

                        $query = "SELECT * FROM banner";
                        $result = @mysqli_query($link, $query);

                        if(mysqli_num_rows($result) > 0){
                            while($registro = mysqli_fetch_assoc($result)){
                        ?>
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2 card-hover">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            <?php echo $registro["nome"];?>
                                        </div>
                                        
                                        <div class="text-center mb-3">
                                            <img src="update/<?php echo $registro["path"];?>" alt="Banner" class="card-banner-img">
                                        </div>

                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs text-gray-500">
                                                    Data: <?php echo date('d/m/Y', strtotime($registro["data"])); ?>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <a href="update/eliminar_banner.php?id=<?php echo $registro["id_banner"];?>" 
                                                   class="btn btn-danger btn-circle btn-sm" 
                                                   onclick="return confirm('Tem certeza que deseja excluir este banner?');"
                                                   title="Excluir Banner">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php
                            }
                        } else {
                        ?>
                            <div class="col-12">
                                <div class="alert alert-info text-center shadow-sm" role="alert">
                                    <h4 class="alert-heading">Nenhum banner encontrado!</h4>
                                    <p>Sua lista de banners está vazia no momento.</p>
                                    <hr>
                                    <p class="mb-0">Clique no botão "Adicionar Novo Banner" acima para começar.</p>
                                </div>
                            </div>
                        <?php
                        }
                        DB_Close($link);
                        ?>

                    </div> </div>
                </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>LUIZÃO ATACAREJO &copy; ADM 2025</span>
                    </div>
                </div>
            </footer>
            </div>
        </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>