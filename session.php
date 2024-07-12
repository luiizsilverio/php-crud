<?php

  if (!isset($_SESSION))
    session_start();

  $_SESSION['nomeUsuario'] = '';
  $_SESSION['emailUsuario'] = '';
  $_SESSION['admin'] = '';
  $_SESSION['idUsuario'] = '';

?>
