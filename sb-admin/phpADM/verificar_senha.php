<?php
session_start();
include 'conexao.php';
include 'configuracao.php';

$conn = DB_connect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $senhaDigitada = $_POST["senha"];

    $sql = "SELECT senha FROM admin WHERE usuario = 'cod' ";
    $resultado = $conn->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        $senhaCorreta = $row["senha"];

        if (password_verify($senhaDigitada, $senhaCorreta)) {
            header("Location: ../register.php");
            exit();
        } else {
            echo "<script>alert('Senha incorreta!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Erro ao buscar a senha no banco.'); window.history.back();</script>";
    }

    // Fecha a conexão
    DB_Close($conn);
}
?>
