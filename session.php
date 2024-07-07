<?php

  if (!isset($_SESSION))
    session_start();

  $_SESSION['nomeUsuario'] = '';
  $_SESSION['emailUsuario'] = '';

?>