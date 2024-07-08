<?php include 'lib/conexao.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PHP CRUD | Lista de Clientes</title>
  <style>
    body {
      padding: 0 24px;
    }
    /* table, th, td {
      border: 1px solid;
    } */
    table {
      border-collapse: collapse;

    }
    tr:nth-child(even) {
      background-color: #f2f2f2;
    }
    th {
      background-color: navy;
      color: white;
    }
  </style>
</head>

<body>

  <?php
    $sql = 'SELECT * FROM clientes ORDER BY id DESC';
    $result = $db_connect->query($sql) or die($db_connect->error);
    $qtd_clientes = $result->num_rows;
  ?>

  <h1>Lista de Clientes</h1>

  <table border="1" cellpadding="12" >
    <thead>
      <th>ID</th>
      <th>Nome</th>
      <th>E-mail</th>
      <th>Telefone</th>
      <th>Dt. Nascimento</th>
      <th>Dt. Cadastro</th>
      <th>Ações</th>
    </thead>
    <tbody>
      <?php 
        if ($qtd_clientes == 0) { ?>
          <tr>
            <td colspan="7">Nenhum cliente foi cadastrado!</td>
          </tr>
      <?php 
        } else {
          while ($row = $result->fetch_assoc()) { 
            $dtcad = date('d/m/Y H:i', strtotime($row['dtcad']));
            $dtnas = $row['dtnas'];
            if (!empty($dtnas))
              $dtnas = date('d/m/Y', strtotime($dtnas));
          ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
              <td><?php echo $row['nome']; ?></td>
              <td><?php echo $row['email']; ?></td>
              <td><?php echo $row['telefone']; ?></td>
              <td><?php echo $dtnas; ?></td>
              <td><?php echo $dtcad; ?></td>
              <td>
                <a href="editar-cliente.php?id=<?php echo $row['id']; ?>">Alterar</a>
                <a href="excluir-cliente.php?id=<?php echo $row['id']; ?>">Excluir</a>
              </td>
            </tr>
      <?php }} ?>
    </tbody>
  </table>

  <br>
  <a href="cadastrar-cliente.php">Incluir novo cliente</a>
  
</body>
</html>