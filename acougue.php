<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luizão Atacarejo - Açougue</title>
    <link rel="icon" type="image/x-icon" href="imagens/logotipo.jpg">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link href='https://cdn.boxicons.com/fonts/brands/boxicons-brands.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/styleHome.css">
    <link rel="stylesheet" href="css/acougue.css">
    <link rel="stylesheet" href="css/responsivo.css">
</head>

<body>
    <header>
        <div class="menu">
            <button id="mobile-menu-toggle" class="mobile-menu-button">
                <i class='bx bx-menu'></i>
            </button>
            <div class="logo">
                <a href="index.php"><img src="imagens/logo.png" alt="logo" /></a>
            </div>
            <div class="search-container">
                <form action="" method="POST" class="search-bar">
                    <input type="search" class="search-input" name="search" placeholder="O que você procura?">
                    <button type="submit" class="search-button">
                        <i class='bx bx-search'></i>
                    </button>
                </form>
            </div>
            <nav>
                <a href="contato.html">Fale conosco</a>
            </nav>
            <div class="navcart">
                <i class='bx bx-cart' style="font-size: 24px; color: white;"></i>
                <span>0</span>
            </div>
        </div>
    </header>

    <?php
    require_once 'php/configuracao.php';
    require_once 'php/conexao.php';
    $link = DB_connect();
    
    // Verifica busca
    $is_search = ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['search']));
    ?>

    <div id="mobile-menu-panel" class="mobile-menu-panel">
        <div class="mobile-menu-header">
            <a href="index.php" class="mobile-menu-logo">
                <img src="imagens/logo.png" alt="Ir para a página inicial" />
            </a>
            <button id="close-mobile-menu-btn" class="mobile-menu-close-btn">
                <i class='bx bx-x'></i>
            </button>
        </div>
        <div class="mobile-menu-content">
            <nav class="mobile-menu-nav">
                <a href="mercearia.php">Mercearia</a>
                <a href="hortali.php">Hortifruti</a>
                <a href="acougue.php">Açougue</a>
                <a href="limpeza.php">Limpeza</a>
                <a href="higiene.php">Higiene</a>
                <a href="frios.php">Frios e Laticínios</a>
                <a href="bebidas.php">Bebidas</a>
                <a href="padaria.php">Padaria</a>
                <a href="contato.html">Fale conosco</a>
            </nav>
        </div>
    </div>

    <div class="menu-categorias">
        <div class="dropdown">
            <button class="dropdown-toggle">
                Todas as categorias <i class='bx bx-chevron-down'></i>
            </button>
            <div class="dropdown-menu">
                <a href="bebidas.php" class="dropdown-item">Bebidas</a>
                <a href="frios.php" class="dropdown-item">Frios e Laticínios</a>
                <a href="padaria.php" class="dropdown-item">Padaria</a>
                <a href="mercearia.php" class="dropdown-item">Mercearia</a>
                <a href="acougue.php" class="dropdown-item">Açougue</a>
                <a href="hortali.php" class="dropdown-item">Hortifruti</a>
                <a href="limpeza.php" class="dropdown-item">Limpeza</a>
            </div>
        </div>
        <nav class="categorias-nav">
            <a href="mercearia.php">Mercearia</a>
            <a href="hortali.php">Hortifruti</a>
            <a href="acougue.php">Açougue</a>
            <a href="limpeza.php">Limpeza</a>
            <a href="higiene.php">Higiene</a>
            <a href="frios.php">Frios e Laticínios</a>
        </nav>
    </div>

    <?php
    // --- LÓGICA DE BUSCA ---
    if ($is_search) {
        $search = mysqli_real_escape_string($link, $_POST['search']);
        $sql = "SELECT * FROM produtos WHERE nome LIKE '%$search%'";
        $result = mysqli_query($link, $sql);
    ?>
        <section class="produtos" id="resultados-busca" style="padding: 2% 8%; background-color: #fff;">
            <div class="cabeçalho dois">
                <h1>Resultados para "<?php echo htmlspecialchars($search); ?>"</h1>
            </div>
            <div class="produtos-container"> 
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                        <div class="box <?php echo $row["subcategoria"]; ?>" 
                             data-nome="<?php echo htmlspecialchars($row["nome"]); ?>"
                             data-preco="<?php echo $row["preco"]; ?>">
                            
                            <div class="img-container">
                                <img src="sb-admin/phpADM/<?php echo $row["path"]; ?>" alt="<?php echo htmlspecialchars($row["nome"]); ?>" />
                                <div class="cart card-add-btn <?php echo $row["status"]; ?>"><i class='bx bx-plus'></i></div>
                                <button class="quick-view-btn" 
                                        data-nome="<?php echo htmlspecialchars($row["nome"]); ?>"
                                        data-preco="R$ <?php echo $row["preco"]; ?>"
                                        data-descricao="por <?php echo $row["descricao"]; ?>"
                                        data-imagem="sb-admin/phpADM/<?php echo $row["path"]; ?>">
                                    <i class='bx bx-search-alt-2'></i>
                                </button>
                                <div class="card-quantity-selector">
                                    <button class="qty-btn qty-remove"><i class='bx bx-trash'></i></button>
                                    <span class="qty-value">1</span>
                                    <button class="qty-btn qty-add"><i class='bx bx-plus'></i></button>
                                </div>
                            </div> 
                            <span><?php echo $row['categoria']; ?></span>
                            <h2> <?php echo $row["nome"]; ?> </h2>
                            <h3 class="preço"> R$ <?php echo $row["preco"];?><span> por <?php echo $row["descricao"]; ?></span></h3>
                        </div>
                <?php
                    }
                } else {
                    echo "<p style='text-align:center; font-size:1.2rem; margin-top:20px; width:100%;'>Nenhum produto encontrado com esse nome.</p>";
                }
                ?>
            </div>
        </section>
    <?php
    } else {
        
        
        $itens_por_pagina = 10; 
        $pagina_atual = filter_input(INPUT_GET, 'pagina', FILTER_SANITIZE_NUMBER_INT);
        $pagina = (!empty($pagina_atual)) ? $pagina_atual : 1;
        $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

        // Captura o filtro da URL
        $filtro_sub = filter_input(INPUT_GET, 'sub', FILTER_SANITIZE_SPECIAL_CHARS);

        // Monta o SQL Base (usado para produtos E paginação)
        $where_sql = "WHERE categoria = 'Açougue'";
        if (!empty($filtro_sub)) {
            $sub_safe = mysqli_real_escape_string($link, urldecode($filtro_sub));
            $where_sql .= " AND subcategoria = '$sub_safe'";
        }
    ?>

        <main class="subcategoria-content">
            <aside class="filter-sidebar">
                <button id="close-filter-btn" class="close-filter-button">
                    <i class='bx bx-x'></i>
                </button>
                <div class="icones">
                    <a href="index.php">Inicio</a>
                    <span>></span>
                    <span>Açougue</span>
                </div>
                
                <div class="filter-group">
                    <div class="filter-group-header">
                        <h3>Subcategorias</h3>
                    </div>
                    
                    <a href="acougue.php" class="filter-link <?php echo (empty($filtro_sub)) ? 'active' : ''; ?>">
                        <span>Ver Todos</span>
                        <i class='bx bx-grid-alt'></i>
                    </a>

                    <a href="acougue.php?sub=bovina" class="filter-link <?php echo ($filtro_sub == 'bovina') ? 'active' : ''; ?>">
                        <span>Carnes bovinas</span>
                        <i class='bx bx-chevron-right'></i>
                    </a>
                    <a href="acougue.php?sub=aves" class="filter-link <?php echo ($filtro_sub == 'aves') ? 'active' : ''; ?>">
                        <span>Aves e Frangos</span>
                        <i class='bx bx-chevron-right'></i>
                    </a>
                    <a href="acougue.php?sub=suina" class="filter-link <?php echo ($filtro_sub == 'suina') ? 'active' : ''; ?>">
                        <span>Carnes suínas</span>
                        <i class='bx bx-chevron-right'></i>
                    </a>
                    <a href="acougue.php?sub=peixe" class="filter-link <?php echo ($filtro_sub == 'peixe') ? 'active' : ''; ?>">
                        <span>Peixes</span>
                        <i class='bx bx-chevron-right'></i>
                    </a>
                    <a href="acougue.php?sub=embutidos" class="filter-link <?php echo ($filtro_sub == 'embutidos') ? 'active' : ''; ?>">
                        <span>Embutidos (linguiça, salsicha)</span>
                        <i class='bx bx-chevron-right'></i>
                    </a>
                </div>
            </aside>

            <section class="acougue" id="produtos">
                <div class="cabeçalho dois">
                    <h1> 
                    <?php 
                        // Título Amigável (Converte 'bovina' para 'Carnes Bovina')
                        $titulos_amigaveis = [
                            'bovina'    => 'Carnes Bovina',
                            'aves'      => 'Aves e Frangos',
                            'suina'     => 'Carnes Suínas',
                            'peixe'     => 'Peixaria',
                            'embutidos' => 'Embutidos e Defumados'
                        ];

                        if (!empty($filtro_sub)) {
                            if (array_key_exists($filtro_sub, $titulos_amigaveis)) {
                                echo $titulos_amigaveis[$filtro_sub];
                            } else {
                                echo ucfirst(htmlspecialchars(urldecode($filtro_sub)));
                            }
                        } else {
                            echo "Carnes, Aves & Peixaria";
                        }
                    ?> 
                    </h1>
                </div>
                
                <div class="mobile-controls">
                    <button id="open-filter-btn" class="filter-button">
                        <i class='bx bx-filter-alt'></i> Filtrar
                    </button>
                </div>
                
                <div class="container">
                    <?php
                        // Executa a busca com Filtro e Limite
                        $query = "SELECT * FROM produtos $where_sql LIMIT $inicio, $itens_por_pagina";
                        $result = mysqli_query($link, $query);
                        
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($registro = mysqli_fetch_assoc($result)) {
                    ?>
                            <div class="box <?php echo $registro["subcategoria"]; ?>" 
                                data-nome="<?php echo htmlspecialchars($registro["nome"]); ?>"
                                data-preco="<?php echo $registro["preco"]; ?>">
                                
                                <div class="img-container">
                                    <img src="sb-admin/phpADM/<?php echo $registro["path"]; ?>" alt="<?php echo htmlspecialchars($registro["nome"]); ?>" />
                                    
                                    <div class="cart card-add-btn <?php echo $registro["status"]; ?>"><i class='bx bx-plus'></i></div>

                                    <button class="quick-view-btn" 
                                            data-nome="<?php echo htmlspecialchars($registro["nome"]); ?>"
                                            data-preco="R$ <?php echo $registro["preco"]; ?>"
                                            data-descricao="por <?php echo $registro["descricao"]; ?>"
                                            data-imagem="sb-admin/phpADM/<?php echo $registro["path"]; ?>">
                                        <i class='bx bx-search-alt-2'></i>
                                    </button>
                                    
                                    <div class="card-quantity-selector">
                                        <button class="qty-btn qty-remove"><i class='bx bx-trash'></i></button>
                                        <span class="qty-value">1</span>
                                        <button class="qty-btn qty-add"><i class='bx bx-plus'></i></button>
                                    </div>
                                    
                                </div> 
                                <span>Açougue</span>
                                <h2> <?php echo $registro["nome"]; ?> </h2>
                                <h3 class="preço"> R$ <?php echo $registro["preco"];?><span> por <?php echo $registro["descricao"]; ?></span></h3>
                            </div>
                            
                            <?php
                            }
                        } else {
                            echo "<div style='width: 100%; text-align: center; padding: 40px; grid-column: 1 / -1;'>";
                            echo "<h3>Nenhum produto encontrado.</h3>";
                            echo "</div>";
                        }
                    ?>
                </div>

                <?php
                // Conta total de registros para criar os botões
                $result_pg = "SELECT COUNT(id) AS num_result FROM produtos $where_sql";
                $resultado_pg = mysqli_query($link, $result_pg);
                $row_pg = mysqli_fetch_assoc($resultado_pg);
                
                $quantidade_pg = ceil($row_pg['num_result'] / $itens_por_pagina);

                $link_base = "acougue.php?";
                if (!empty($filtro_sub)) {
                    $link_base .= "sub=" . urlencode($filtro_sub) . "&";
                }

                if ($quantidade_pg > 1) {
                ?>
                    <div class="paginacao">
                        <?php
                        if ($pagina > 1) {
                            echo "<a href='" . $link_base . "pagina=" . ($pagina - 1) . "' class='prev'>&lt;</a>";
                        }
                        
                        for ($i = 1; $i <= $quantidade_pg; $i++) {
                            // Lógica para não mostrar muitos botões se tiver muitas páginas
                            if ($i == 1 || $i == $quantidade_pg || ($i >= $pagina - 2 && $i <= $pagina + 2)) {
                                $ativo = ($i == $pagina) ? 'active' : '';
                                echo "<a href='" . $link_base . "pagina=$i' class='$ativo'>$i</a>";
                            } elseif ($i == $pagina - 3 || $i == $pagina + 3) {
                                echo "<span>...</span>";
                            }
                        }

                        if ($pagina < $quantidade_pg) {
                            echo "<a href='" . $link_base . "pagina=" . ($pagina + 1) . "' class='next'>&gt;</a>";
                        }
                        ?>
                    </div>
                <?php } ?>

            </section>
        </main>
    <?php
    } 
    DB_Close($link);
    ?>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-close">
            <i class='bx bx-x'></i>
        </div>
        <div class="cart-menu">
            <h3>Meu Carrinho</h3>
            <div class="cart-item">Adicione algum produto aqui!</div>
        </div>
        <div class="sidebar-footer">
            <div class="total">
                <h5>Total: </h5>
                <div class="cart-total">R$ 0,00</div>
            </div>
            <button class="compra-btn">Finalizar compra</button>
        </div>
    </div>

    <div id="quick-view-overlay" class="quick-view-overlay">
        <div id="quick-view-modal" class="quick-view-modal">
            <button id="quick-view-close" class="quick-view-close">&times;</button>
            <div class="quick-view-content">
                <img id="quick-view-img" src="" alt="Imagem do Produto" />
                <div class="quick-view-details">
                    <h2 id="quick-view-nome">Nome do Produto</h2>
                    <h3 id="quick-view-preco">R$ 00,00 <span>por kg</span></h3>
                    <button class="add-to-cart-btn">Adicionar ao Carrinho</button> 
                </div>
            </div>
        </div>
    </div>

    <div class="rodape">
        <div class="rodape-container">
            <div class="rodape-row">
                <div class="rodape-col">
                    <h4>Navegação</h4>
                    <div class="linha">
                        <a href="index.php#produtos">Produtos</a>
                        <a href="index.php#categorias">Categorias</a>
                    </div>
                </div>
                <div class="rodape-col">
                    <h4>Serviços</h4>
                    <div class="linha">
                        <a href="contato.html">Fale conosco!</a>
                        <a href="contato.html#apresentacao">Sobre nós</a>
                    </div>
                </div>
                <div class="rodape-col social-links">
                    <h4>Nossas redes sociais</h4>
                    <a href="https://www.instagram.com/luizaotacarejo" target="_blank"><i class='bxl bx-instagram' style='color:#11213c'></i></a>
                    <a href="https://api.whatsapp.com/send/?phone=61996576211&text&type=phone_number&app_absent=0" target="_blank"><i class='bxl bx-whatsapp' style='color:#11213c'></i></a>
                </div>
            </div>
            <div class="footer-info">
                <p> <b>Horário de atendimento:</b> <br> De segunda a domingo, das 07 às 22h. <br><br>Avenida planalto quadra t lote 2 - São Gabriel, Planaltina - GO | CEP: 73758-000 </p>
                <a> Luizão Atacarejo - Todos os Direitos Reservados.</a>
            </div>
        </div>
    </div>

    <script src="script.js?v=<?php echo time(); ?>" defer></script>
    <div id="filter-overlay"></div>

</body>
</html>