<?php session_start(); ?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css" />
  <title>PHP CRUD | Login</title>
</head>

<body>
  <?php
    if (isset($_SESSION['idUsuario'])) {
      $email = $_SESSION['emailUsuario'];
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      include 'lib/conexao.php';

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

        $sql = "SELECT * FROM clientes WHERE email = '{$email}'";
	
        $result = $db_connect->query($sql) or die($db_connect->error);

        $row = $result->fetch_assoc();

        if ($result->num_rows == 0) {
          $erro = 'O e-mail informado não foi encontrado';
        }
        elseif (!password_verify($senha, $row['senha'])) {
          $erro = "Senha ou e-mail incorreto!";
        } 
        else {
          $_SESSION['idUsuario'] = $row['id'];
          $_SESSION['nomeUsuario'] = $row['nome'];
          $_SESSION['emailUsuario'] = $email;
          $_SESSION['admin'] = $row['admin'];

          header("Location: clientes.php");        
        }
      }

      if (!empty($erro)) {
        session_unset();
      }
    }
  ?>

  <div class="container">
    <h1>Acesso ao Sistema</h1>

    <form action="" method="post">
      <p>
        <label for="email">E-mail:</label><br>
        <input type="text" id="email" name="email" value="<?php echo $email; ?>" />
      </p>
      <p>
        <label for="senha">Senha:</label><br>
        <input type="password" id="senha" name="senha" value="<?php echo $senha; ?>" />
      </p>

      <p style="color: red"><?php echo $erro; ?></p>
      
      <p>
        <button type="submit">Entrar</button>
      </p>
      <small>
        Não tem senha? <a href="signup.php">Cadastre-se!</a>
      </small>
      <!-- <pre><?php var_dump($_SESSION); ?></pre> -->
    </form>
  </div>
 
</body>
</html>