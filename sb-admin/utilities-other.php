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
                Interface
            </div>

        

            <li class="nav-item active">
                <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Ajuste de página</span>
                </a>
                <div id="collapseUtilities" class="collapse show" aria-labelledby="headingUtilities"
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
                        <a class="collapse-item"  href="" data-toggle="modal" data-target="#senhaModal">Registrar outro ADM</a>
                        <div class="collapse-divider"></div>
                        
                    </div>
                </div>
            </li>

                <div class="modal fade" id="perfilModal" tabindex="-1" role="dialog" aria-labelledby="perfilModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    
                    <?php
                        //Realizando a conexão com o banco
                        require_once 'phpADM/configuracao.php'; 
                        require_once 'phpADM/conexao.php';
                        if(!isset($link) || !$link) $link = DB_connect();

                        // Busca ID correto da sessão
                        $id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 0;
                        
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
                        <a href="phpADM/logout.php" class="btn btn-primary">Logout</a>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
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

                </nav>
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Produtos em oferta</h1>
                    </div>
                <div>

                                    <div class="col-lg-20">

                                <?php
                                        // Garante conexão
                                        if(!isset($link) || !$link) $link = DB_connect();

                                        //Consulta SQL de inserção:
                                        $query = "SELECT * FROM ofertas"; 
                                        $result = @mysqli_query($link, $query);
                                ?>

                                    <div class="produtos-container">
                                        <?php
                                        
                                                    function formatarTaxa($valor) {
                                                        if (floor($valor) == $valor) {
                                                            return number_format($valor, 0, ',', '.'); // sem casas decimais
                                                        } else {
                                                            return number_format($valor, 2, ',', '.'); // com duas casas decimais
                                                        }
                                                    }

                                        if (mysqli_num_rows($result) > 0) {
                                            while ($registro = mysqli_fetch_assoc($result)) {
                                                ?>
                                                <div class="box">
                                                    <span>Data lançada: <?php echo $registro["data_criada"];?></span>
                                                    <img src="phpADM/<?php echo $registro["path"];?>" alt="" />
                                                    <span><?php echo $registro["categoria"];?></span>
                                                    <h3 class="preço"> 
                                                        Preço antigo: R$<?php echo $registro["preco"];?><br>
                                                        Preço da oferta: R$<?php echo $registro["precoFinal"];?> 
                                                        <span> por <?php echo $registro["descricao"];?> </span>
                                                        Desconto de: <?php echo formatarTaxa($registro["taxa"]);?>%
                                                    </h3>
                                                    <h2> <?php echo $registro["nome"];?> </h2>
                                                    <a href="update/eliminar_oferta.php?id=<?php echo $registro["id_promo"];?>">
                                                        <div class="edit">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                                                class="bi bi-trash3" viewBox="0 0 16 16">
                                                                <path
                                                                    d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                                            </svg>
                                                        </div>
                                                    </a>
                                                </div>
                                                <?php
                                            }
                                        } else {
                                            echo "<p style='text-align: center; font-size: 1.2rem; margin-top: 20px;'>Nenhuma oferta encontrada no momento.</p>";
                                        }

                                        DB_Close($link);
                                        ?>

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

</body>

<style>

    .produtos-container{
        display: grid;
        gap: 1.5rem;
        margin-top: 2rem;
        padding: 1rem;
        flex: 1;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    }
    
    .produtos-container .box{
        padding: 15px;
        border-radius: 0.5rem;
        position: relative;
        box-shadow: 1px 2px 10px 4px rgba(14, 55, 54, 0.038);
        border: 1px solid rgba(111, 112, 113, 0.208);
    }

    .produtos-container .box img {
        width: 100%;
        height: 130px;
        object-fit: contain;
        object-position: center;
    }

    .produtos-container .box span {
        font-weight: 300;
        font-size: 12px;
    }

    .produtos-container .box h2 {
        font-size: 0.9rem;
        font-weight: 400;
    }

    .produtos-container .box .preço {
        font-size: 0.9em;
        font-weight: 600;
        margin-top: 0.5rem;
        color: rgb(163, 12, 12);
    }

    .produtos-container .box .desconto{
        position: absolute;
        top: 1rem;
        left: 0;
        background-color: #eb1d25;
        color: white;
        padding: 4px 18px;   
    }

    .produtos-container .box .edit{
        background-color: #11213c;
        color: white;
        position: relative;
        right: 0;
        bottom: 0;
        border-radius: 3px;
        height: 40px;
        width: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
    }

    .box .edit:hover{
        background-color: #686e78;
    } 

</style>

</html>