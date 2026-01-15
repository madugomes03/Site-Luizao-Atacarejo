<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../phpADM/configuracao.php'; 
require '../phpADM/conexao.php';
$link = DB_connect();

if (!isset($_GET['id'])) {
    die("Erro: ID do produto não fornecido.");
}

$id = intval($_GET['id']); 
$taxa = $_POST['taxa'];

$queryProduto = "SELECT * FROM produtos WHERE id = $id"; 
$result = mysqli_query($link, $queryProduto);

if ($registro = mysqli_fetch_assoc($result)) {
    $nome = mysqli_real_escape_string($link, $registro["nome"]);
    $descricao = mysqli_real_escape_string($link, $registro["descricao"]);
    $categoria = mysqli_real_escape_string($link, $registro["categoria"]);
    $imagemAtual = $registro["path"];

    $precoBruto = $registro["preco"];
    $precoLimpo = preg_replace('/[^0-9,]/', '', $precoBruto);
    $precoPonto = str_replace(',', '.', $precoLimpo);
    $preco = floatval($precoPonto);

    $taxaV = floatval(str_replace(',', '.', $taxa)); 
    $desconto = $preco * ($taxaV / 100.0);
    $precoFinal = $preco - $desconto;
    
    $precoSQL = number_format($preco, 2, '.', '');
    $precoFinalSQL = number_format($precoFinal, 2, '.', '');
    $taxaSQL = number_format($taxaV, 2, '.', '');

    $pathParaBanco = $imagemAtual; 

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $imagem = $_FILES['imagem'];

        if ($imagem['size'] > 5097152) { 
            die("<script>alert('Arquivo muito grande!'); window.history.back();</script>");
        }

        $extensao = strtolower(pathinfo($imagem['name'], PATHINFO_EXTENSION));
        if (!in_array($extensao, ['jpg', 'jpeg', 'png', 'webp'])) {
            die("<script>alert('Formato de arquivo inválido!'); window.history.back();</script>");
        }

        $pastaDestino = "../phpADM/arquivos/"; 
        if (!is_dir($pastaDestino)) {
            mkdir($pastaDestino, 0777, true);
        }

        $nomeUnico = uniqid("oferta_") . '.' . $extensao;
        $caminhoFisico = $pastaDestino . $nomeUnico;
        
        $pathParaBanco = "arquivos/" . $nomeUnico; 

        if (!move_uploaded_file($imagem['tmp_name'], $caminhoFisico)) {
            echo "<script>alert('Falha ao salvar a imagem.'); window.history.back();</script>";
            DB_Close($link);
            exit;
        }
    }

    $data_criada = date('Y-m-d H:i:s');

    $queryOferta = "INSERT INTO ofertas (precoFinal, id_produto, nome, descricao, preco, taxa, categoria, path, data_criada) 
                    VALUES ('$precoFinalSQL', '$id', '$nome', '$descricao', '$precoSQL', '$taxaSQL', '$categoria', '$pathParaBanco', '$data_criada')";

    $resultOferta = mysqli_query($link, $queryOferta);

    if ($resultOferta) {
        echo "<script>alert('Oferta cadastrada com sucesso!'); window.location.href = '../utilities-animation.php';</script>";
    } else {
        die("Erro fatal no banco de dados: " . mysqli_error($link));
    }

} else {
    echo "<script>alert('Produto não encontrado.'); window.location.href = '../painel.html';</script>";
}

DB_Close($link);
?>