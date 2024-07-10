<?php include 'lib/conexao.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <title>PHP CRUD | Lista de Clientes</title>
</head>

<body>

  <?php
    $sql = 'SELECT * FROM clientes ORDER BY id DESC';
    $result = $db_connect->query($sql) or die($db_connect->error);
    $qtd_clientes = $result->num_rows;
  ?>

  <div class="container">
    <h1>Lista de Clientes</h1>
    <p>
      <input type="submit" value="Incluir Novo Cliente" onclick="location.href='cadastrar-cliente.php';" />
    </p>

    <table border="1" cellpadding="12" >
      <thead>
        <th>ID</th>
        <th>Adm</th>
        <th>Foto</th>
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
                <td>
                  <?php if ($row['admin']) { ?>
                  <i class="bi bi-star" style="color: green"></i>
                  <?php } ?>
                </td>
                <td>
                  <?php if (!empty($row['foto'])) { ?>
                    <img src="<?php echo $row['foto']; ?>" alt="foto do cliente" height="50" />
                  <?php } ?>
                </td>
                <td><?php echo $row['nome']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['telefone']; ?></td>
                <td><?php echo $dtnas; ?></td>
                <td><?php echo $dtcad; ?></td>
                <td>                  
                  <a href="editar-cliente.php?id=<?php echo $row['id']; ?>">
                    <i class="bi bi-pencil-square" title="Alterar" style="color: blue"></i>
                  </a>
                  <a href="excluir-cliente.php?id=<?php echo $row['id']; ?>">
                    <i class="bi bi-x-circle" title="Excluir" style="color: red"></i>
                  </a>
                </td>
              </tr>
        <?php }} ?>
      </tbody>
    </table>
  </div>

</body>
</html>