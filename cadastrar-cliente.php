<?php  
  session_start();
  if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header("Location: clientes.php");
    die();
  }
?>
  
<!DOCTYPE html>
<html lang="pt">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>PHP CRUD</title>
  </head>
  
  <body>
    <?php include "header.php";

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          include 'lib/conexao.php';
          include 'lib/mail.php';
          include 'lib/upload.php';

          $nome     = $_POST['nome'];
          $email    = $_POST['email'];
          $telefone = $_POST['telefone'];
          $dtnas    = $_POST['dtnas'];
          $senha    = $_POST['senha'];
          $admin    = $_POST['admin'];
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
          elseif (isset($_FILES['foto']) && !empty($_FILES['foto']['name'])) {   // Upload de foto
            $arq = $_FILES['foto'];
            $path = enviarArquivo($arq['error'], $arq['size'], $arq['name'], $arq['tmp_name']);
            if ($path == false) 
              $erro = 'Falha ao enviar a foto';
          }

          if (empty($erro)) {
            $hash = password_hash($senha, PASSWORD_DEFAULT);

            $sql = "INSERT INTO clientes (nome, email, telefone, dtnas, senha, foto, admin) 
                    VALUES ('$nome', '$email', '$telefone', '$dtnas', '$hash', '$path', '$admin')";

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

    <div class="container">
      <h1>Novo Cliente</h1>

      <form action="" method="post" enctype="multipart/form-data">
        <p>
          <label for="nome">Nome</label><br>
          <input type="text" name="nome" id="nome" value="<?php echo $nome; ?>" /> 
        </p>
        <p>
          <label for="email">E-mail:</label><br>
          <input type="email" name="email" id="email" value="<?php echo $email; ?>" /> 
        </p>
        <p>
          <label for="telefone">Telefone:</label><br>
          <input type="text" name="telefone" id="telefone" 
            placeholder="(99) 9999-9999"
            value="<?php echo $telefone; ?>" 
          /> 
        </p>
        <p>
          <label for="dtnas">Dt. Nascimento:</label><br>
          <input type="date" name="dtnas" id="dtnas" 
            value="<?php echo $dtnas; ?>" 
          />
        </p>
        <p>
          <label for="senha">Senha:</label><br>
          <input type="password" name="senha" id="senha" 
            value="<?php echo $senha; ?>" 
          />
        </p>
        <p>
          <label for="foto">Foto</label>
          <input type="file" name="foto" id="foto" />
        </p>
        <p>
          <label>Tipo:</label>
          <input type="radio" name="admin" value="1" />ADMIN
          <input type="radio" name="admin" value="0" checked />CLIENTE
        </p>
        <p>
          <p style="color: red; font-style=bold;"><?php echo $erro; ?></p>
          <p style="color: blue; font-style=bold;"><?php echo $sucesso; ?></p>
          <button type="submit">Salvar Cliente</button>
        </p>
      </form>
    </div>
  </body>
</html>