<!DOCTYPE html>
<html lang="pt">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP CRUD</title>
  </head>
  
  <body>
    
    <?php

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          include 'conexao.php';
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
          else {
            $sql = "INSERT INTO clientes (nome, email, telefone, dtnas) VALUES ('$nome', '$email', '$telefone', '$dtnas')";

            try {
              $db_connect->query($sql);
              $sucesso = "Cliente cadastrado com sucesso!";
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

  <a href="">Voltar para a lista</a>
  <form action="" method="post" >
    <p>
      <label for="nome">Nome</label>
      <input type="text" name="nome" id="nome" value="<?php echo $nome; ?>" /> 
    </p>
    <p>
      <label for="email">E-mail:</label>
      <input type="email" name="email" id="email" value="<?php echo $email; ?>" /> 
    </p>
    <p>
      <label for="telefone">Telefone:</label>
      <input type="text" name="telefone" id="telefone" 
        placeholder="(99) 9999-9999"
        value="<?php echo $telefone; ?>" 
      /> 
    </p>
    <p>
      <label for="dtnas">Dt. Nascimento:</label>
      <input type="date" name="dtnas" id="dtnas" 
        value="<?php echo $dtnas; ?>" 
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