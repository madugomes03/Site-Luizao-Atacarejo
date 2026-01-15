<?php 

require '../phpADM/configuracao.php'; 
require '../phpADM/conexao.php';

$link = DB_connect();

// Verifica se houve POST (envio de formulário)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nome = mysqli_real_escape_string($link, $_POST['nome']);
    $data_cadastro = date('Y-m-d');

    // Verifica imagem
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        
        $imagem_tmp = $_FILES['imagem']['tmp_name'];
        $imagem_nome = basename($_FILES['imagem']['name']);

        // Define a pasta banners. 
        // __DIR__ garante que o PHP procure a pasta no lugar certo (dentro de update)
        $diretorio_destino = __DIR__ . '/banners/';
        
        // Verifica se a pasta existe, se não, tenta criar
        if (!is_dir($diretorio_destino)) {
            mkdir($diretorio_destino, 0755, true);
        }

        // Caminho final para salvar
        $nome_final = uniqid() . '_' . $imagem_nome;
        $path_absoluto = $diretorio_destino . $nome_final;
        $path_banco = 'banners/' . $nome_final; // Caminho relativo para salvar no banco

        if (move_uploaded_file($imagem_tmp, $path_absoluto)) {
            
            $sql = "INSERT INTO banner (nome, path, data) VALUES ('$nome', '$path_banco', '$data_cadastro')";
            
            if (mysqli_query($link, $sql)) {
                echo "<script>alert('Banner cadastrado com sucesso!'); window.location.href = '../cards.php';</script>";
            } else {
                echo "Erro SQL: " . mysqli_error($link);
            }
        } else {
            echo "Erro ao mover arquivo. Verifique as permissões da pasta 'banners'. Caminho tentado: " . $path_absoluto;
        }
    } else {
        echo "<script>alert('Selecione uma imagem válida!'); window.history.back();</script>";
    }
} else {
    // Se entrar na página sem enviar formulário, redireciona ou avisa
    echo "Nenhum dado enviado. Certifique-se de acessar pelo formulário.";
}

DB_Close($link);
?>