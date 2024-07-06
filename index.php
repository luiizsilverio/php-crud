<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PHP CRUD | Login</title>
  <style>
    body {
      padding: 0 24px;
    }
  </style>
</head>

<body>
  <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      include 'conexao.php';

      $email = $_POST['email'];
      $senha = $_POST['senha'];

      if (empty($email)) {
        $erro = 'O e-mail é obrigatório';
      } 
      elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'E-mail inválido';
      } 
      elseif (empty($senha)) {
        $erro = 'Senha é obrigatória';
      }
      else {

        $sql = "SELECT * FROM usuarios WHERE email = '{$email}'";
	
        $result = $db_connect->query($sql) or die($db_connect->error);

        $row = $result->fetch_assoc();

        if (!password_verify($senha, $row['senha'])) {
          $erro = "Senha ou e-mail incorreto!";
        } else {
          header("Location: http://localhost/php-crud/clientes.php");        
        }
      }
    }
  ?>

  <h1>Acesso ao Sistema</h1>

  <form action="" method="post">
    <p>
      <label for="email">E-mail:</label>
      <input type="text" id="email" name="email" value="<?php echo $email; ?>" />
    </p>
    <p>
      <label for="senha">Senha:</label>
      <input type="password" id="senha" name="senha" value="<?php echo $senha; ?>" />
    </p>

    <p style="color: red"><?php echo $erro; ?></p>
    
    <p>
      <button type="submit">Confirma</button>
    </p>
    <a href="signup.php">Não tem senha? Cadastre-se!</a>
  </form>
 
</body>
</html>