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

    $nome     = $cliente['nome'];
    $email    = $cliente['email'];
    $telefone = $cliente['telefone'];
    $dtnas    = $cliente['dtnas'];
    $foto     = $cliente['foto'];
    $admin    = $cliente['admin'] ?? "0";
    
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
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>PHP CRUD | Alterar Cliente</title>
  </head>
  
  <body>
    <?php include "header.php";

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          include 'lib/upload.php';

          $nome      = $_POST['nome'];
          $email     = $_POST['email'];
          $telefone  = $_POST['telefone'];
          $dtnas     = $_POST['dtnas'];
          $senha     = $_POST['senha'];
          $admin     = $_POST['admin'];
          $erro      = "";
          $sql_foto  = "";
          $sql_senha = "";

          $alterou_senha = !empty($senha);

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
          
          if ($alterou_senha && (strlen($senha) < 4 || strlen($senha) > 16)) {
            $erro = 'A senha deve ter tamanho entre 6 e 16 caracteres';
          }

          if (isset($_FILES['foto']) && !empty($_FILES['foto']['name'])) {   // Upload de foto
            $arq = $_FILES['foto'];
            $path = enviarArquivo($arq['error'], $arq['size'], $arq['name'], $arq['tmp_name']);
            if ($path == false) 
              $erro = 'Falha ao enviar a foto';
            else {
              $sql_foto = " foto = '$path', ";
              $foto = $path;
            }
          }

          if (!$erro) {

            if ($alterou_senha) {
              $hash = password_hash($senha, PASSWORD_DEFAULT);
              $sql_senha = " senha = '$hash', ";
            }

            $sql = "UPDATE clientes 
                    SET nome = '$nome', 
                    email = '$email', 
                    telefone = '$telefone', 
                    {$sql_senha}
                    {$sql_foto}
                    dtnas = '$dtnas',
                    admin = $admin
                    WHERE id = {$id}";

            try {
              $db_connect->query($sql);
              $sucesso = "Cliente atualizado com sucesso!";

              if (!empty($cliente['foto']) && !empty($sql_foto)) 
                unlink($cliente['foto']); // apaga a foto antiga
              
            }
            catch (Exception $e) {
              $erro = "Erro ao enviar dados: " . $db_connect->error;
            }
          }
      }
    ?>

    <div class="container">
      <h1>Alteração do Cliente</h1>
      
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
          <label for="senha">Senha (se não for alterar, deixe em branco)</label><br>
          <input type="password" name="senha" id="senha" />
        </p>

        <?php if (!empty($foto)) { ?>
          <p>
            <label>Foto Atual:</label><br>
            <img src="<?php echo $foto; ?>" value="<?php echo $foto; ?> alt="Foto atual" height="50" />
          </p>
        <?php } ?>

        <p>
          <label for="foto">Nova Foto</label><br>
          <input type="file" name="foto" id="foto" />
        </p>
        <p>
          <label>Tipo:</label>
          <input type="radio" name="admin" value="1" 
            <?php if ($admin == "1") echo "checked"; ?>
          />ADMIN
          <input type="radio" name="admin" value="0" 
            <?php if ($admin == "0") echo "checked"; ?> 
          />CLIENTE
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