<!DOCTYPE html>
<html lang="pt">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP CRUD</title>
  </head>
  
  <body>
    <?php include "header.php" ?>

    <h1>Novo Cliente</h1>
    
    <?php

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          include 'lib/conexao.php';
          include 'lib/mail.php';
          include 'lib/upload.php';

          $nome     = $_POST['nome'];
          $email    = $_POST['email'];
          $telefone = $_POST['telefone'];
          $dtnas    = $_POST['dtnas'];
          $senha    = $_POST['senha'];
          $erro     = "";
          $path     = "";

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
          elseif (strlen($senha) < 4 || strlen($senha) > 16) {
            $erro = 'A senha deve ter tamanho entre 6 e 16 caracteres';
          }
          elseif (isset($_FILES['foto'])) {   // Upload de foto
            if (isset($_FILES['foto'])) {
              $arq = $_FILES['foto'];
              $path = enviarArquivo($arq['error'], $arq['size'], $arq['name'], $arq['tmp_name']);
              if ($path == false) 
                $erro = 'Falha ao enviar a foto';
            }
          }

          if (empty($erro)) {
            $hash = password_hash($senha, PASSWORD_DEFAULT);

            $sql = "INSERT INTO clientes (nome, email, telefone, dtnas, senha, foto) 
                    VALUES ('$nome', '$email', '$telefone', '$dtnas', '$hash', '$path')";

            try {
              $db_connect->query($sql);
              $sucesso = "Cliente cadastrado com sucesso!";

              enviar_email($email, "Sua conta foi criada!", "
                <h1>Parabéns!</h1>
                <p>Sua conta no nosso site foi criada!</p>
                <p>
                  <strong>Login:</strong> $email <br>
                  <strong>Senha:</strong> $senha <br>
                </p>         
                <p>Para fazer login, acesse <a href=\"http://localhost/php-crud/index.php\">nosso site</a>.</p>       
              ");

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

  <form action="" method="post" enctype="multipart/form-data">
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
      <label for="senha">Senha:</label>
      <input type="password" name="senha" id="senha" 
        value="<?php echo $senha; ?>" 
      />
    </p>
    <p>
      <label for="foto">Foto</label>
      <input type="file" name="foto" id="foto" />
    </p>
    <p>
      <p style="color: red; font-style=bold;"><?php echo $erro; ?></p>
      <p style="color: blue; font-style=bold;"><?php echo $sucesso; ?></p>
      <button type="submit">Salvar Cliente</button>
    </p>
  </form>
</body>
</html>