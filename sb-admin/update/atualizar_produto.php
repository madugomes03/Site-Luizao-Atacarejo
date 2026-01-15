<?php 
// 1. Recebimento do ID e Conexão
$id = $_GET['id'];
require '../phpADM/configuracao.php'; 
require '../phpADM/conexao.php';
$link = DB_connect();

// 2. Inicializa variáveis vazias
$nome = "";
$descricao = "";
$categoria = "";
$subcategoria = "";
$preco = "";
$status = "";
$imagem = "";

// 3. Busca no Banco
$query = "SELECT * FROM produtos WHERE id = '$id'"; 
$result = @mysqli_query($link, $query);

if ($result && $registro = mysqli_fetch_assoc($result)){
    $nome = $registro["nome"];
    $descricao = $registro["descricao"];
    $categoria = $registro["categoria"];
    $subcategoria = $registro["subcategoria"];
    $preco = $registro["preco"];
    $status = $registro["status"];
    $imagem = $registro["path"];
}


$nome_exibicao = $subcategoria;

$lista_nomes = [
    // Mercearia
    'graos' => 'Alimentos Básicos',
    'farinha' => 'Farinhas e Farofas',
    'oleos' => 'Itens Gerais',
    'enlatados' => 'Enlatados',
    'cafe' => 'Biscoitos e Ensacados',
    'massas' => 'Massas e Molhos',
    
    // Higiene
    'bucal' => 'Higiene bucal',
    'banho' => 'Sabonetes e Banho',
    'cabelo' => 'Cabelos',
    'pessoal' => 'Cuidados Pessoais',
    'papel' => 'Higiene íntima',
    
    // Limpeza
    'sabao' => 'Limpeza de Roupas',
    'detergente' => 'Limpeza de Cozinha',
    'desinfetante' => 'Limpeza de Banheiro',
    'utensilios' => 'Limpeza Geral',
    
    // Açougue
    'bovina' => 'Carne Bovina',
    'aves' => 'Aves e Frango',
    'suina' => 'Carne Suína',
    'peixe' => 'Peixes',
    'embutidos' => 'Embutidos',
    
    // Padaria (Atualizado: Apenas Bolos)
    'pao' => 'Pães',
    'doce' => 'Bolos', 
    'salgado' => 'Salgados',
    'outros' => 'Outras opções',
    
    // Bebidas
    'refrigerante' => 'Refrigerantes/Energético',
    'suco' => 'Sucos e Refrescos',
    'cerveja' => 'Alcoólicas',
    'destilados' => 'Bebidas Lácteas e Outros',
    
    // Frios (Atualizado: Apenas Iogurtes)
    'iogurte' => 'Iogurtes',
    'leite' => 'Leites',
    'manteiga' => 'Manteigas e Margarinas',
    'queijo' => 'Queijo e Requeijão',
    'fatiados' => 'Outras opções'
];

// Se o código existir na lista, pega o nome bonito
if (array_key_exists($subcategoria, $lista_nomes)) {
    $nome_exibicao = $lista_nomes[$subcategoria];
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualização do produto</title>
    <link rel="stylesheet" href="../css/Fstyle.css">
</head>

<body>

    <div class="container">
        <form class="formum" action="../phpADM/atualizar.php?id=<?php echo $id; ?>&categoria=<?php echo $categoria; ?>" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="column">
                    <h3 class="title">Atualização dos produtos</h3>
                    
                    <div class="input-box">
                        <span>Produto :</span>
                        <input type="text" name="nome" placeholder="Ex.: Contra Filé Bovino" value="<?php echo $nome;?>">
                    </div>

                    <div class="input-box">
                        <span>Unidade de medida :</span>
                        <input type="text" name="descricao" placeholder="Ex.: Kilo ou Grama" value="<?php echo $descricao ;?>">
                    </div>

                    <div class="input-box">
                        <span>Subcategoria :</span>
                        <select name="subcategoria">
                            
                            <option selected value="<?php echo $subcategoria; ?>"><?php echo $nome_exibicao; ?> (Atual)</option>

                            <?php
                            // Normaliza para maiúsculo para comparar
                            $cat_banco = strtoupper($categoria);

                            // --- AÇOUGUE ---
                            if ($cat_banco == "AÇOUGUE" || $cat_banco == "ACOUGUE" || $categoria == "Açougue") {
                                echo '<option value="bovina">Carne Bovina</option>';
                                echo '<option value="aves">Aves e Frango</option>';
                                echo '<option value="suina">Carne Suína</option>';
                                echo '<option value="peixe">Peixes</option>';
                                echo '<option value="embutidos">Embutidos</option>';
                            }
                            
                            // --- HORTIFRUTI ---
                            elseif ($cat_banco == "HORTIFRUTI") {
                                echo '<option value="frutas">Frutas</option>';
                                echo '<option value="legume">Legumes</option>';
                                echo '<option value="verduras">Verduras</option>';
                                echo '<option value="temperos">Temperos</option>';
                            }

                            // --- BEBIDAS ---
                            elseif ($cat_banco == "BEBIDAS") {
                                echo '<option value="refrigerante">Refrigerantes/Energético</option>';
                                echo '<option value="suco">Sucos e Refrescos</option>';
                                echo '<option value="cerveja">Alcoólicas</option>';
                                echo '<option value="destilados">Bebidas Lácteas e Outros</option>';
                                echo '<option value="agua">#</option>';
                            }

                            // --- FRIOS E LATICÍNIOS ---
                            elseif ($cat_banco == "FRIOS E LATICÍNIOS" || $cat_banco == "FRIOS E LATICINIOS" || strpos($cat_banco, 'FRIOS') !== false) {
                                echo '<option value="iogurte">Iogurtes</option>';
                                
                                echo '<option value="leite">Leites</option>';
                                echo '<option value="manteiga">Manteigas e Margarinas</option>';
                                echo '<option value="queijo">Queijo e Requeijão</option>';
                                echo '<option value="fatiados">Outras opções</option>';
                            }
                           
                            // --- HIGIENE ---
                            elseif ($cat_banco == "HIGIENE" || $cat_banco == "PERFUMARIA") {
                                echo '<option value="bucal">Higiene bucal</option>';
                                echo '<option value="banho">Sabonetes e Banho</option>';
                                echo '<option value="cabelo">Cabelos</option>';
                                echo '<option value="pessoal">Cuidados Pessoais</option>';
                                echo '<option value="papel">Higiene íntima</option>';
                            }

                            // --- LIMPEZA ---
                            elseif ($cat_banco == "LIMPEZA") {
                                echo '<option value="sabao">Limpeza de Roupas</option>';
                                echo '<option value="detergente">Limpeza de Cozinha</option>';
                                echo '<option value="desinfetante">Limpeza de Banheiro</option>';
                                echo '<option value="utensilios">Limpeza Geral</option>';
                            }

                            // --- PADARIA ---
                            elseif ($cat_banco == "PADARIA") {
                                echo '<option value="pao">Pães</option>';
                                echo '<option value="doce">Bolos</option>';
                                echo '<option value="salgado">Salgados</option>';
                                echo '<option value="outros">Outras opções</option>';
                            }

                            // --- MERCEARIA ---
                            elseif ($cat_banco == "MERCEARIA") {
                                echo '<option value="graos">Alimentos Básicos</option>';
                                echo '<option value="farinha">Farinhas e Farofas</option>'; 
                                echo '<option value="oleos">Itens Gerais</option>';
                                echo '<option value="enlatados">Enlatados</option>';
                                echo '<option value="cafe">Biscoitos e Ensacados</option>';
                                echo '<option value="massas">Massas e Molhos</option>';
                            }
                            
                            // Caso genérico
                            else {
                                echo '<option value="">Sem opções específicas para: ' . $categoria . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="flex">
                        <div class="input-box">
                            <span>Valor :</span>
                            <input name="preco" type="text" placeholder="99,99" value="<?php echo $preco;?>">
                        </div>
                    </div>

                    <div class="input-box-radio">
                        <span>Vai estar disponível para venda? :</span><br><br>
                        <input type="radio" id="disponivel" name="status" value="disponivel" required <?php if($status == 'disponivel') echo 'checked'; ?>>
                        <label for="disponivel">Sim</label><br>

                        <input type="radio" id="indisponivel" name="status" value="indisponivel" <?php if($status == 'indisponivel') echo 'checked'; ?>>
                        <label for="indisponivel">Não</label>
                    </div>

                    <div class="input-box imgbd">
                        <span>Imagem atual</span>
                        <?php
                        if (!empty($imagem)) {
                            echo '<img src="../phpADM/' . $imagem . '" width="40%" style="display: block; margin: 0 auto;">';
                        } else {
                            echo "<p>Sem imagem.</p>";
                        }
                        ?>
                    </div>
                    
                    <div class="input-box img">
                        <span>Atualizar imagem :</span>
                        <input type="file" name="imagem">
                    </div>

                </div>
            </div>

            <button type="submit" class="btn">Atualizar produto</button>
            <br><br>
            <a href="../phpADM/deletar.php?id=<?php echo $id; ?>" onclick="return confirm('Tem certeza que deseja deletar?');">
                <button type="button" class="btn" style="background-color: #d9534f;">Deletar produto</button>
            </a>
        </form>
    </div>

</body>
</html>