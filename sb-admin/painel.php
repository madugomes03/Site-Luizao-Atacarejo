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
                        <a class="collapse-item" href="" data-toggle="modal" data-target="#senhaModal">Registrar outro ADM</a>
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

                        // CORREÇÃO: Pegando ID correto da sessão
                        $id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 0;

                        // Busca direta do usuário logado
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
                            <p class="text-danger">Erro: Perfil não encontrado. Faça login novamente.</p>
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

                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

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

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                LUIZÃO ATACAREJO 
                                            </div>
                                            <a href="formularios/acougueF.html" class="stretched-link">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">AÇOUGUE</div>
                                            </a>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                LUIZÃO ATACAREJO
                                            </div>
                                            <a href="formularios/bebidaF.html" class="stretched-link">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">BEBIDAS</div>
                                            </a>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                LUIZÃO ATACAREJO
                                            </div>
                                            <a href="formularios/friosF.html" class="stretched-link">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">FRIOS/LATICINIOS</div>
                                            </a>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                LUIZÃO ATACAREJO
                                            </div>
                                            <a href="formularios/higieneF.html" class="stretched-link">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">HIGIENE</div>
                                            </a>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                LUIZÃO ATACAREJO
                                            </div>
                                            <a href="formularios/hortifrutiF.html" class="stretched-link">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">HORTIFRUTI</div>
                                            </a>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                LUIZÃO ATACAREJO
                                            </div>
                                            <a href="formularios/limpezaF.html" class="stretched-link">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">LIMPEZA</div>
                                            </a>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                LUIZÃO ATACAREJO
                                            </div>
                                            <a href="formularios/padariaF.html" class="stretched-link">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">PADARIA</div>
                                            </a>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                LUIZÃO ATACAREJO
                                            </div>
                                            <a href="formularios/merceariaF.html" class="stretched-link">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">MERCEARIA</div>
                                            </a>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-12 col-lg-4">

                            <div class="card shadow mb-4">
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Banners atuais</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Ações:</div>
                                            <a class="dropdown-item" href="cards.php">Página dos banners</a>
                                            <a class="dropdown-item" href="update/bannerF.html">Adicionar banners</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body d-flex justify-content-center">

                                    <div class="banner w-100" style="max-width: 600px;">

                                        <div id="carouselExample" class="carousel slide">
                                            <div class="carousel-inner">

                                                <?php
                                                // Verifica se a conexão ainda está aberta, se não, abre novamente
                                                if(!isset($link) || !$link) {
                                                    require_once 'phpADM/configuracao.php'; 
                                                    require_once 'phpADM/conexao.php';
                                                    $link = DB_connect();
                                                }

                                                $query = "SELECT * FROM banner";
                                                $result = @mysqli_query($link, $query);

                                                if(mysqli_num_rows($result) > 0){
                                                    $primeiro = true;
                                                    while($registro = mysqli_fetch_assoc($result)){
                                                        $activeClass = $primeiro ? 'active' : '';
                                                        $primeiro = false;
                                                ?>

                                                <div class="carousel-item <?php echo $activeClass; ?>">
                                                    <img src="update/<?php echo $registro["path"]; ?>" 
                                                        alt="<?php echo $registro["nome"]; ?>" 
                                                        class="d-block w-100">
                                                </div>

                                                <?php
                                                    }
                                                } else {
                                                    echo "<p style='white-space: nowrap; font-size: 1.2rem; margin-top: 20px;'>Parece que não tem nenhum banner para visualização.<br>Cadastre algum que mostrará aqui!</p>";
                                                }

                                                DB_Close($link);
                                                ?>

                                            </div>

                                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Anterior</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Próximo</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>LUIZÃO ATACAREJO &copy; ADM LUIZÃO</span>
                    </div>
                </div>
            </footer>
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