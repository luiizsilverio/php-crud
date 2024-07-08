<?php include 'lib/conexao.php'; 

  $id = intval($_GET['id']);

  if ($id == 0) 
    die("Cliente não encontrado!");

  $sql = "SELECT nome FROM clientes WHERE id = {$id}";
  
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
    <title>PHP CRUD | Excluir Cliente</title>
  </head>
  
  <body>
    <?php include "header.php";
    
      if ($_SERVER['REQUEST_METHOD'] != 'POST') { ?>
        <h2>Tem certeza de que deseja excluir este cliente?</h2>
        <h3><?php echo $cliente['nome']; ?></h3>

        <form action="" method="post">
          <input type="button" onclick="location.href='clientes.php';" value="Não" />
          <button type="submit">Sim</button>
        </form>
      <?php } ?>

    
    <?php

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {    
        $sql = "DELETE from clientes WHERE id = {$id}";

        try {
          if ($db_connect->query($sql)) {
            echo "<h2>Cliente excluído com sucesso!</h2>";;
          }
        }
        catch (Exception $e) {
          die($db_connect->error);
        }
      }

    ?>

</body>
</html>