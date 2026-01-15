<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'php/configuracao.php';
require_once 'php/conexao.php';
$link = DB_connect();
mysqli_set_charset($link, "utf8mb4");


$is_search = ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['search']));

// --- FUNÇÃO GERADORA DE PRODUTOS ---
function criarCardProduto($prod, $isOferta = false) {
    $nomeSafe = htmlspecialchars($prod['nome'], ENT_QUOTES);
    $descSafe = htmlspecialchars($prod['descricao'], ENT_QUOTES);
    $pathImg  = "sb-admin/phpADM/" . $prod['path'];
    
    if ($isOferta && isset($prod['precoFinal'])) {
        $preco = $prod['precoFinal'];
        $taxa  = isset($prod['taxa']) ? floor($prod['taxa']) : 0;
    } else {
        $preco = $prod['preco'];
    }
    $precoVisual = number_format((float)str_replace(',', '.', $preco), 2, ',', '.');
    $precoData   = str_replace(',', '.', $preco);

    ?>
    <div class="box" 
         data-nome="<?php echo $nomeSafe; ?>" 
         data-preco="<?php echo $precoData; ?>">
        
        <div class="img-container">
            <img src="<?php echo $pathImg; ?>" alt="<?php echo $nomeSafe; ?>" loading="lazy" />
            
            <div class="cart card-add-btn disponivel">
                <i class='bx bx-plus'></i>
            </div>

            <button class="quick-view-btn" 
                    data-nome="<?php echo $nomeSafe; ?>"
                    data-preco="R$ <?php echo $precoVisual; ?>"
                    data-descricao="por <?php echo $descSafe; ?>"
                    data-imagem="<?php echo $pathImg; ?>">
                <i class='bx bx-search-alt-2'></i>
            </button>
            
            <div class="card-quantity-selector">
                <button class="qty-btn qty-remove"><i class='bx bx-trash'></i></button>
                <span class="qty-value">1</span>
                <button class="qty-btn qty-add"><i class='bx bx-plus'></i></button>
            </div>

            <?php if ($isOferta) { ?>
                <span class="desconto">Oferta! <?php echo $taxa; ?>%</span>
            <?php } ?>
        </div>
        
        <span><?php echo $prod['categoria']; ?></span>
        <h2><?php echo $prod['nome']; ?></h2>
        <h3 class="preço"> R$ <?php echo $precoVisual; ?> <span> por <?php echo $descSafe; ?> </span></h3>
    </div>
    <?php
}

function criarSecaoVitrine($conexao, $titulo, $linkVerMais, $sql) {
    // Cria um ID único para cada carrossel para o botão saber qual rolar
    static $contador = 0;
    $contador++;
    $carouselId = "carousel-" . $contador;

    $resultado = mysqli_query($conexao, $sql);
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        ?>
        <div class="secao-titulo">
            <h2><?php echo $titulo; ?></h2>
            
            <div class="nav-wrapper">
                <div class="nav-btns">
                    <button class="nav-arrow" onclick="scrollCarousel('<?php echo $carouselId; ?>', -300)">
                        <i class='bx bx-chevron-left'></i>
                    </button>
                    <button class="nav-arrow" onclick="scrollCarousel('<?php echo $carouselId; ?>', 300)">
                        <i class='bx bx-chevron-right'></i>
                    </button>
                </div>
                <a href="<?php echo $linkVerMais; ?>">Ver tudo ></a>
            </div>
        </div>

        <div class="carrossel-produtos" id="<?php echo $carouselId; ?>">
            <?php
            while ($prod = mysqli_fetch_assoc($resultado)) {
                $isOferta = isset($prod['precoFinal']); 
                criarCardProduto($prod, $isOferta);
            }
            ?>
        </div>
        <?php
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="css/styleHome.css">
    <link rel="icon" type="image/png" href="imagens/logotipo.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/responsivo.css?v=10">
    <title>Luizão Atacarejo</title>
</head>

<body>
    <header>
        <div class="menu">
            <button id="mobile-menu-toggle" class="mobile-menu-button"><i class='bx bx-menu'></i></button>
            <div class="logo">
                <a href="index.php"><img src="imagens/logo.png" alt="logo" /></a>
            </div>
            <div class="search-container">
                <form action="" method="POST" class="search-bar">
                                    <input type="search" id="search" class="search-input" name="search" placeholder="O que você procura?">
                    <button type="submit" class="search-button"><i class='bx bx-search'></i></button>
                </form>
            </div>
            <nav><a href="contato.html">Fale conosco</a></nav>
            <div class="navcart"> 
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="20" fill="#ffffff" viewBox="0 0 24 24">
                    <path d="M10.5 18a1.5 1.5 0 1 0 0 3 1.5 1.5 0 1 0 0-3M17.5 18a1.5 1.5 0 1 0 0 3 1.5 1.5 0 1 0 0-3M8.82 15.77c.31.75 1.04 1.23 1.85 1.23h6.18c.79 0 1.51-.47 1.83-1.2l3.24-7.4c.14-.31.11-.67-.08-.95S21.34 7 21 7H7.33L5.92 3.62C5.76 3.25 5.4 3 5 3H2v2h2.33zM19.47 9l-2.62 6h-6.18l-2.5-6z"></path>
                </svg>
                <span>0</span>
            </div>
        </div>
    </header>

    <div id="mobile-menu-panel" class="mobile-menu-panel">
        <div class="mobile-menu-header">
            <a href="index.php" class="mobile-menu-logo"><img src="imagens/logo.png" alt="Home" /></a>
            <button id="close-mobile-menu-btn" class="mobile-menu-close-btn"><i class='bx bx-x'></i></button>
        </div>
        <div class="mobile-menu-content">
            <div class="dropdown mobile-dropdown">
                <button class="dropdown-toggle"><i class='bx bxs-grid-alt'></i> Todas as categorias </i></button>
            </div>
            <nav class="mobile-menu-nav">
                <a href="mercearia.php">Mercearia</a>
                <a href="hortali.php">Hortifruti</a>
                <a href="acougue.php">Açougue</a>
                <a href="limpeza.php">Limpeza</a>
                <a href="higiene.php">Higiene</a>
                <a href="frios.php">Frios e Laticínios</a>
                <a href="bebidas.php">Bebidas</a>
                <a href="padaria.php">Padaria</a>
                <a href="contato.html">Fale Conosco</a>
            </nav>
        </div>
    </div>

    <div class="menu-categorias">
        <div class="dropdown">
            <button class="dropdown-toggle">Todas as categorias <i class='bx bx-chevron-down'></i></button>
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
    // === CONTEÚDO PRINCIPAL ===
    if ($is_search) {
        $search = mysqli_real_escape_string($link, $_POST['search']);
        $sql = "SELECT * FROM produtos WHERE nome LIKE '%$search%'";
        $result = mysqli_query($link, $sql);
    ?>
        <section class="produtos" style="padding: 2% 8%; background-color: #fff;">
            <div class="cabeçalho dois"><h1>Resultados para "<?php echo htmlspecialchars($search); ?>"</h1></div>
            <div class="produtos-container"> 
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        criarCardProduto($row, false);
                    }
                } else {
                    echo "<p style='width:100%; text-align:center;'>Nenhum produto encontrado.</p>";
                }
                ?>
            </div>
        </section>
    <?php
    } else {
        // === HOME PAGE ===
    ?>
        <div class="banner">
            <div id="carouselExample" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                <div class="carousel-inner">
                    <?php
                    $result_banner = mysqli_query($link, "SELECT * FROM banner");
                    if ($result_banner && mysqli_num_rows($result_banner) > 0) {
                        $primeiro = true;
                        while ($registro = mysqli_fetch_assoc($result_banner)) {
                            echo '<div class="carousel-item '.($primeiro?'active':'').'"><img src="sb-admin/update/'.$registro["path"].'" class="d-block w-100"></div>';
                            $primeiro = false;
                        }
                    }
                    ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
            </div>
        </div>

        <section class="categorias" id="categorias">
            <div class="cabeçalho"><h1><span>Navegue pelas categorias</span></h1></div>
            <div class="categorias-container">
                <div class="div box1"><a href="mercearia.php"><img src="imagens/mercearia.png" /></a><h2>Mercearia</h2></div>
                <div class="div box2"><a href="hortali.php"><img src="imagens/frutaas.png" /></a><h2>Hortifruti</h2></div>
                <div class="div box3"><a href="acougue.php"><img src="imagens/açougue.png" /></a><h2>Açougue</h2></div>
                <div class="div box4"><a href="limpeza.php"><img src="imagens/produtos de limpeza.png" /></a><h2>Limpeza</h2></div>
                <div class="div box5"><a href="higiene.php"><img src="imagens/produtos_de_higiene.png" /></a><h2>Higiene</h2></div>
            </div>
        </section>

        <?php
            // VITRINES
            criarSecaoVitrine($link, " Aproveite as melhores ofertas!", "#", "SELECT * FROM ofertas LIMIT 10");
            criarSecaoVitrine($link, " Açougue", "acougue.php", "SELECT * FROM produtos WHERE categoria LIKE 'Açougue%' OR categoria LIKE 'Acougue%' ORDER BY RAND() LIMIT 8");
            criarSecaoVitrine($link, " Hortifruti", "hortali.php", "SELECT * FROM produtos WHERE categoria LIKE 'Hortifruti%' OR categoria LIKE 'Legumes%' ORDER BY RAND() LIMIT 8");
            criarSecaoVitrine($link, " Mercearia", "mercearia.php", "SELECT * FROM produtos WHERE categoria = 'Mercearia' LIMIT 8");
        ?>

    <?php
    } // Fim do Else (Home)
    DB_Close($link);
    ?>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-close"><i class='bx bx-x'></i></div>
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
                        <a href="#produtos">Produtos</a>
                        <a href="#categorias">Categorias</a>
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
                <a href="">&copy;2025 Luizão Atacarejo - Todos os Direitos Reservados.</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js" defer></script>
    
    <script>
        function scrollCarousel(elementId, distance) {
            const container = document.getElementById(elementId);
            if (container) {
                container.scrollBy({
                    left: distance,
                    behavior: 'smooth'
                });
            }
        }
    </script>
</body>
</html>