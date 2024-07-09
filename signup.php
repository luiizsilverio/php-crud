<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PHP CRUD | Sign Up</title>
  <style>
    body {
      padding: 0 24px;
    }
  </style>
</head>

<body>

  <?php
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      include 'lib/conexao.php';
      include 'lib/helpers.php';

      $nome = $_POST['nome'];
      $email = $_POST['email'];
      $senha = $_POST['senha'];
      $senha2 = $_POST['senha2'];

      if (empty($nome)) {
        $erro = 'O nome é obrigatório';
      } 
      // verifica se o nome contém somente letras e espaço
      elseif (!preg_match("/^[a-zA-Z-' ]*$/", $nome)) {
        $erro = "Somente letras e espaço em branco permitido";
      }
      elseif (empty($email)) {
        $erro = 'O e-mail é obrigatório';
      } 
      elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'E-mail inválido';
      } 
      elseif (empty($senha) || empty($senha2)) {
        $erro = 'Senha é obrigatória';
      }
      elseif ($senha != $senha2) {
        $erro = 'Senhas não conferem';
      }
      else {
        $nome = clear_input($nome); // função clean_input no código abaixo
        $email = clear_input($email);
        $hash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('{$nome}', '{$email}', '{$hash}')";
	
        $result = $db_connect->query($sql) or die($db_connect->error);

        header("Location: http://localhost/php-crud/");        
      }
    }
  ?>

  <h1>Cadastre-se</h1>

  <form action="" method="post">
    <p>
      <label for="nome">Nome:</label>
      <input type="text" id="nome" name="nome" value="<?php echo $nome; ?>" />
    </p>
    <p>
      <label for="email">E-mail:</label>
      <input type="text" id="email" name="email" value="<?php echo $email; ?>" />
    </p>
    <p>
      <label for="senha">Senha:</label>
      <input type="password" id="senha" name="senha" value="<?php echo $senha; ?>" />
    </p>
    <p>
      <label for="senha2">Confirme a senha:</label>
      <input type="password" id="senha2" name="senha2" value="<?php echo $senha2; ?>" />
    </p>

    <p style="color: red"><?php echo $erro; ?></p>
    
    <p>
      <button type="submit">Confirma</button>
    </p>
    
    <a href="index.php">Voltar</a>
  </form>
 
</body>
</html>