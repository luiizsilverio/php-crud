<?php include 'lib/conexao.php'; 

  $id = intval($_GET['id']);

  if ($id == 0) 
    die("Cliente não encontrado!");

  $sql = "SELECT * FROM clientes WHERE id = {$id}";

  
  try {
    $result = $db_connect->query($sql) or die($db_connect->error);
    $qtd_clientes = $result->num_rows;

    if ($qtd_clientes == 0) 
      die("Cliente não encontrado!");

    $cliente = $result->fetch_assoc();
  }
  catch (Exception $e) {
    $erro = "Erro ao enviar dados: " . $db_connect->error;
  }

?>

<!DOCTYPE html>
<html lang="pt">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP CRUD | Alterar Cliente</title>
  </head>
  
  <body>
    <?php include "header.php" ?>

    <h1>Alteração do Cliente</h1>
    
    <?php

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          $nome     = $_POST['nome'];
          $email    = $_POST['email'];
          $telefone = $_POST['telefone'];
          $dtnas    = $_POST['dtnas'];
          $erro     = "";

          $email = filter_var($email, FILTER_SANITIZE_EMAIL);

          if (empty($nome)) 
            $erro = "Nome é obrigatório <br>";
          elseif (empty($email))
            $erro = "E-mail é obrigatório <br>";
          elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
            $erro = 'E-mail inválido';
          elseif (!empty($dtnas) && !strtotime($dtnas)) {
            $erro = 'Data inválida';
          }
          else {
            $sql = "UPDATE clientes 
                    SET nome = '$nome', 
                    email = '$email', 
                    telefone = '$telefone', 
                    dtnas = '$dtnas' 
                    WHERE id = {$id}";

            try {
              $db_connect->query($sql);
              $sucesso = "Cliente atualizado com sucesso!";
              // unset($_POST);
              $nome = '';
              $email = '';
              $telefone = '';
              $dtnas = '';

            }
            catch (Exception $e) {
              $erro = "Erro ao enviar dados: " . $db_connect->error;
            }
          }
      }

    ?>

  <form action="" method="post" >
    <p>
      <label for="nome">Nome</label>
      <input type="text" name="nome" id="nome" value="<?php echo $cliente['nome']; ?>" /> 
    </p>
    <p>
      <label for="email">E-mail:</label>
      <input type="email" name="email" id="email" value="<?php echo $cliente['email']; ?>" /> 
    </p>
    <p>
      <label for="telefone">Telefone:</label>
      <input type="text" name="telefone" id="telefone" 
        placeholder="(99) 9999-9999"
        value="<?php echo $cliente['telefone']; ?>" 
      /> 
    </p>
    <p>
      <label for="dtnas">Dt. Nascimento:</label>
      <input type="date" name="dtnas" id="dtnas" 
        value="<?php echo $cliente['dtnas']; ?>" 
      />
    </p>
    <p>
      <p style="color: red; font-style=bold;"><?php echo $erro; ?></p>
      <p style="color: blue; font-style=bold;"><?php echo $sucesso; ?></p>
      <button type="submit">Salvar Cliente</button>
    </p>
  </form>
</body>
</html>