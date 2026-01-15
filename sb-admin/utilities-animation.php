<?php
session_start();
// Verifica se o usuário está logado
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

    <title>Painel de controle - Produtos</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        .header-com-filtro {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 10px;
        }

        .filtro-admin select {
            padding: 5px 10px;
            border-radius: 5px;
            border: 1px solid #d1d3e2;
            color: #6e707e;
            background-color: #fff;
            outline: none;
            cursor: pointer;
            font-size: 0.9rem;
        }
    </style>
</head>

<body id="page-top">

    <div id="wrapper">

        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="sbhome.php">
                <div class="sidebar-brand-icon rotate-n-15"></div>
                <div class="sidebar-brand-text mx-3">ADMINISTRADOR</div>
            </a>
            
            <hr class="sidebar-divider my-0">
            
            <li class="nav-item active">
                <a class="nav-link" href="painel.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>CATEGORIAS</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="https://luizaoatacarejo.com.br" target="_blank">
                    <i class="fas fa-fw fa-store"></i>
                    <span>Acessar Site Principal</span></a>
            </li>

            <hr class="sidebar-divider">
            
            <div class="sidebar-heading">Interface</div>
            
            <li class="nav-item active">
                <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Ajuste de página</span>
                </a>
                <div id="collapseUtilities" class="collapse show" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Páginas:</h6>
                        <a class="collapse-item" href="utilities-animation.php">Produtos Cadastrados</a>
                        <a class="collapse-item" href="utilities-other.php">Ofertas em Destaque</a>
                        <a class="collapse-item" href="cards.php">Banners</a>
                    </div>
                </div>
            </li>
            
            <hr class="sidebar-divider">
            
            <div class="sidebar-heading">INTERFACE ADMINISTRADOR</div>
            
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
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
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Pesquise por algum produto" aria-label="Search" aria-describedby="basic-addon2" name="search">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                    </ul>

                </nav>

                <div class="container-fluid page-produtos">
                    
                    <?php
                    // Conexão
                    require_once 'phpADM/configuracao.php';
                    require_once 'phpADM/conexao.php';
                    
                    if(!isset($link) || !$link) {
                         $link = DB_connect();
                    }

                    // === 1. LÓGICA DE BUSCA ===
                    $is_search = ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['search']));
                
                    if ($is_search) {
                        $search = mysqli_real_escape_string($link, $_POST['search']);
                        $sql    = "SELECT * FROM produtos WHERE nome LIKE '%$search%'";
                        $result = mysqli_query($link, $sql);
                        ?>
                        <section class="produtos" id="resultados-busca">
                            <div class="cabecalho-resultados">
                                <h2 class="h4 mb-3 text-gray-800">Resultados para "<?php echo htmlspecialchars($search); ?>"</h2>
                            </div>
                
                            <div class="produtos-container">
                                <?php
                                if ($result && mysqli_num_rows($result) > 0) {
                                    while ($registro = mysqli_fetch_assoc($result)) {
                                        ?>
                                        <div class="box">
                                            <div class="input-box">
                                                <a href="update/ofertaF.php?id=<?php echo $registro["id"]; ?>">
                                                    <button class="butonAddpromo">Add desconto</button>
                                                </a>
                                            </div>
                                            <img src="phpADM/<?php echo $registro["path"]; ?>" alt="" />
                                            <span><?php echo $registro["categoria"]; ?></span>
                                            <h3 class="preço">
                                                R$<?php echo $registro["preco"]; ?>
                                                <span> por <?php echo $registro["descricao"]; ?> </span>
                                            </h3>
                                            <h2><?php echo $registro["nome"]; ?></h2>
                                            <div class="actions">
                                                <a href="update/atualizar_produto.php?id=<?php echo $registro["id"]; ?>">
                                                    <div class="edit"><i class="fas fa-pen"></i></div>
                                                </a>
                                                <a href="phpADM/deletar.php?id=<?php echo $registro["id"]; ?>">
                                                    <div class="delete"><i class="fas fa-trash"></i></div>
                                                </a>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                } else {
                                    echo "<p class='msg-sem-resultado'>Nenhum produto encontrado com esse nome.</p>";
                                }
                                ?>
                            </div>
                        </section>
                        <hr>
                        <?php
                    }
                    ?>

                    <div class="header-com-filtro">
                        <h1 class="h3 mb-0 text-gray-800">Produtos em catálogo</h1>
                        
                        <div class="filtro-admin">
                            <form action="utilities-animation.php" method="GET">
                                <select name="ordem" onchange="this.form.submit()">
                                    <?php 
                                        $ordem_selecionada = filter_input(INPUT_GET, 'ordem', FILTER_SANITIZE_SPECIAL_CHARS);
                                    ?>
                                    <option value="recente" <?php if($ordem_selecionada == 'recente') echo 'selected'; ?>>Mais Recentes</option>
                                    <option value="az" <?php if($ordem_selecionada == 'az') echo 'selected'; ?>>Ordem Alfabética (A-Z)</option>
                                    <option value="za" <?php if($ordem_selecionada == 'za') echo 'selected'; ?>>Ordem Alfabética (Z-A)</option>
                                    <option value="menor_preco" <?php if($ordem_selecionada == 'menor_preco') echo 'selected'; ?>>Menor Preço</option>
                                    <option value="maior_preco" <?php if($ordem_selecionada == 'maior_preco') echo 'selected'; ?>>Maior Preço</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <div class="card card-produtos-geral shadow mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="produtos-container">
                                        <?php
                                        // === 2. PAGINAÇÃO E LISTAGEM GERAL ===
                                        $itens_por_pagina = 30; 
                                        $pagina_atual = filter_input(INPUT_GET, 'pagina', FILTER_SANITIZE_NUMBER_INT);
                                        $pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;
                                        $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

                                        $ordem_sql = "ORDER BY id DESC"; 
                                        
                                        if ($ordem_selecionada == 'az') $ordem_sql = "ORDER BY nome ASC";
                                        elseif ($ordem_selecionada == 'za') $ordem_sql = "ORDER BY nome DESC";
                                        elseif ($ordem_selecionada == 'menor_preco') $ordem_sql = "ORDER BY preco ASC";
                                        elseif ($ordem_selecionada == 'maior_preco') $ordem_sql = "ORDER BY preco DESC";

                                        $query  = "SELECT * FROM produtos $ordem_sql LIMIT $inicio, $itens_por_pagina";
                                        $result = @mysqli_query($link, $query);
                
                                        while ($registro = mysqli_fetch_assoc($result)) {
                                            ?>
                                            <div class="box">
                                                <div class="input-box">
                                                    <a href="update/ofertaF.php?id=<?php echo $registro["id"]; ?>">
                                                        <button class="butonAddpromo">Add desconto</button>
                                                    </a>
                                                </div>
                                                <img src="phpADM/<?php echo $registro["path"]; ?>" alt="" />
                                                <span><?php echo $registro["categoria"]; ?></span>
                                                <h3 class="preço">
                                                    R$<?php echo $registro["preco"]; ?>
                                                    <span> por <?php echo $registro["descricao"]; ?> </span>
                                                </h3>
                                                <h2><?php echo $registro["nome"]; ?></h2>
                                                <div class="actions">
                                                    <a href="update/atualizar_produto.php?id=<?php echo $registro["id"]; ?>">
                                                        <div class="edit"><i class="fas fa-pen"></i></div>
                                                    </a>
                                                    <a href="phpADM/deletar.php?id=<?php echo $registro["id"]; ?>">
                                                        <div class="delete"><i class="fas fa-trash"></i></div>
                                                    </a>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>

                                    <?php
                                    // Paginação
                                    $result_pg = "SELECT COUNT(id) AS num_result FROM produtos";
                                    $resultado_pg = mysqli_query($link, $result_pg);
                                    $row_pg = mysqli_fetch_assoc($resultado_pg);
                                    $quantidade_pg = ceil($row_pg['num_result'] / $itens_por_pagina);

                                    $link_extra = "";
                                    if (!empty($ordem_selecionada)) {
                                        $link_extra = "&ordem=" . urlencode($ordem_selecionada);
                                    }

                                    if ($quantidade_pg > 1) {
                                    ?>
                                        <div class="paginacao">
                                            <?php
                                            if ($pagina > 1) {
                                                echo "<a href='utilities-animation.php?pagina=" . ($pagina - 1) . $link_extra . "' class='prev'>&lt;</a>";
                                            }
                                            for ($i = 1; $i <= $quantidade_pg; $i++) {
                                                if ($i == 1 || $i == $quantidade_pg || ($i >= $pagina - 2 && $i <= $pagina + 2)) {
                                                    $ativo = ($i == $pagina) ? 'active' : '';
                                                    echo "<a href='utilities-animation.php?pagina=$i$link_extra' class='$ativo'>$i</a>";
                                                } elseif ($i == $pagina - 3 || $i == $pagina + 3) {
                                                    echo "<span>...</span>";
                                                }
                                            }
                                            if ($pagina < $quantidade_pg) {
                                                echo "<a href='utilities-animation.php?pagina=" . ($pagina + 1) . $link_extra . "' class='next'>&gt;</a>";
                                            }
                                            ?>
                                        </div>
                                    <?php 
                                    } 
                                    DB_Close($link);
                                    ?>
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

    <div class="modal fade" id="perfilModal" tabindex="-1" role="dialog" aria-labelledby="perfilModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <?php
                // Reabre conexão se necessário
                if(!isset($link) || !$link) {
                    require_once 'phpADM/configuracao.php'; 
                    require_once 'phpADM/conexao.php';
                    $link = DB_connect();
                }
                
                // Busca ID correto
                $id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 0;
                
                // Consulta
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

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    
    <script src="js/sb-admin-2.min.js"></script>

</body>
</html>