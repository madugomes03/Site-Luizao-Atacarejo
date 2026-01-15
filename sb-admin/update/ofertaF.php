<?php 
//Recebe 
$id= $_GET['id'];
require '../phpADM/configuracao.php'; 
require '../phpADM/conexao.php';
$link = DB_connect();

//Consulta SQL de select:
$query = "SELECT * FROM produtos"; 
$result = @mysqli_query($link, $query);
while ($registro = mysqli_fetch_assoc($result)){
    if ($registro["id"]==$id) {
        $nome = $registro["nome"];
        $preco = $registro["preco"];
        $imagem = $registro["path"];
    }
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
        <form action="cadastrar_oferta.php?id=<?php echo $id;?>" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="column">
                    <h3 class="title">Cadastrar ofertas</h3>
                    <div class="input-box">
                        <span>Produto :</span>
                        <input type="text" name="nome" placeholder="Ex.: Contra Filé Bovino" value="<?php echo $nome;?>">
                    </div>

                    <div class="flex">
                        <div class="input-box">
                            <span>Valor atual:</span>
                            <input name="preco" type="text" placeholder="99,99" value="<?php echo $preco;?>">
                        </div>
                        <div class="input-box">
                            <span>Desconto a aplicar (em porcentagem, sem %):</span>
                        <input name="taxa" type="text" placeholder="Ex.: Digite apenas 5 (sem %).">
                        </div>
                    </div>

                    <div class="input-box imgbd">
                        <span>Imagem atual</span>
                        <?php
                        if (!empty($imagem)) {
                            $caminhoImagem = "../phpADM/" . htmlspecialchars($imagem);
                            if (file_exists($caminhoImagem)) {
                                echo '<img src="' . $caminhoImagem . '" alt="Imagem cadastrada no banco de dados" width="100%">';
                            } else {
                                echo "<p>Imagem não encontrada no servidor.</p>";
                            }
                        } else {
                            echo "<p>Nenhuma imagem cadastrada.</p>";
                        }
                        ?>
                    </div>
                    <div class="input-box img">
                        <span>Atualizar imagem :</span>
                        <input type="file" name="imagem" data-image-input>
                    </div>
                    <div class="preview-image">
                        <img data-image-preview height="100%">
                    </div>
                <script>
                    const imageInput = document.querySelector("[data-image-input]");
                    const imagePreviewElement = document.querySelector("[data-image-preview]");

                    imageInput.onchange = () => {
                    const [file] = imageInput.files;
                    imagePreviewElement.src = URL.createObjectURL(file);
                    };
                </script>

                </div>
            </div>

            <button type="submit" class="btn">Lançar promoção</button>
        </form>
    </div>

</body>
</html>