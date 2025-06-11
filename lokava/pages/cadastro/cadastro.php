<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="cadastro.css">
</head>
<body>
    <div class="container">
        <img src="../../src/img/logo.png" alt="logo">
        <h2>Cadastro</h2>

        <?php
        if (isset($_GET['erro'])) {
            echo "<p style='color:red;'>Erro: " . htmlspecialchars($_GET['erro']) . "</p>";
        }
        if (isset($_GET['sucesso'])) {
            echo "<p style='color:green;'>Cadastro realizado com sucesso!</p>";
        }
        ?>

        <form method="POST" action="cadastro.php">
            <input type="text" name="nome" placeholder="Nome" required>
            <input type="text" name="cpf" placeholder="CPF" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <input type="password" name="confirma_senha" placeholder="Confirme sua senha" required>
            <input type="tel" name="telefone" placeholder="Telefone" required>
            <input type="date" name="nascimento" placeholder="Data de nascimento" required>
            <button type="submit">Cadastrar</button>
        </form>

        <p>Já possui uma conta? <a href="../login/login.php">Login!</a></p>
    </div>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Conectar ao banco
    $conn = new mysqli("localhost", "root", "", "lokava");
    if ($conn->connect_error) {
        die("Erro na conexão: " . $conn->connect_error);
    }

    // Coletar dados
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirma = $_POST['confirma_senha'];
    $telefone = $_POST['telefone'];
    $nascimento = $_POST['nascimento'];

    // Verificação básica
    if ($senha !== $confirma) {
        header("Location: cadastro.php?erro=Senhas+não+coincidem");
        exit();
    }

    // Verifica se email já existe
    $stmt = $conn->prepare("SELECT id FROM clientes WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        header("Location: cadastro.php?erro=Email+já+cadastrado");
        exit();
    }

    // Inserir novo usuário
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO clientes (nome, cpf, email, senha, telefone, data_nascimento) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nome, $cpf, $email, $senha_hash, $telefone, $nascimento);

    if ($stmt->execute()) {
        header("Location: cadastro.php?sucesso=1");
    } else {
        header("Location: cadastro.php?erro=Erro+ao+cadastrar");
    }

    $stmt->close();
    $conn->close();
}
?>
