<?php include 'lib/conexao.php'; 

  $id = intval($_GET['id']);

  if ($id == 0) 
    die("Cliente não encontrado!");

  $sql = "SELECT nome, foto FROM clientes WHERE id = {$id}";
  
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
    <link rel="stylesheet" href="styles.css" />
    <title>PHP CRUD | Excluir Cliente</title>
  </head>
  
  <body>
    <?php include "header.php"; ?>

    <div class="container">

      <?php
        if ($_SERVER['REQUEST_METHOD'] != 'POST') { ?>
          <h2>Tem certeza de que deseja excluir este cliente?</h2>
          <h3><?php echo $cliente['nome']; ?></h3>

          <form action="" method="post">
            <input type="button" 
              onclick="location.href='clientes.php';" 
              value="Não" 
              style="width: 100px"
            />
            <button type="submit" style="width: 100px">Sim</button>
          </form>
      <?php } ?>

    </div>
    
    <?php

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {    
        $sql = "DELETE from clientes WHERE id = {$id}";

        try {
          if ($db_connect->query($sql)) {
            echo "<h2>Cliente excluído com sucesso!</h2>";

            if (!empty($cliente['foto']))
              unlink($cliente['foto']); // apaga a foto antiga
          }
        }
        catch (Exception $e) {
          die($db_connect->error);
        }
      }

    ?>

</body>
</html>