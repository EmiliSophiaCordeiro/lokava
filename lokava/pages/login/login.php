<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
  <div class="container">
    <img src="../../src/img/logo.png" alt="logo">
    <h2>Login</h2>
    
    <?php
    // Exibe mensagem de erro se existir
    if (isset($_GET['erro'])) {
        echo '<p style="color: red;">Email ou senha inválidos.</p>';
    }
    ?>

    <form method="POST" action="login.php">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>

    <p>Ainda não possui uma conta? <a href="../cadastro/cadastro.php">Cadastre-se!</a></p>
  </div>
</body>
</html>

<?php
// Processa o login
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Conexão com o banco
    $conn = new mysqli("localhost", "root", "", "lokava");
    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Busca o usuário na tabela `lokava`
    $stmt = $conn->prepare("SELECT id, senha FROM clientes WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Verifica se encontrou
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $hash);
        $stmt->fetch();

        // Verifica a senha
        if (password_verify($senha, $hash)) {
            session_start();
            $_SESSION['usuario_id'] = $id;
            header("Location: ../principal/principal.html");
            exit();
        }
    }
    else{
        // Redireciona com erro
        header("Location: login.php?erro=1");
        exit();
    }


}
?>
