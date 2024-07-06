<?php

  function clear_input($data) {
    $clean_data = trim($data);
    
    // retira barras invertidas
    $clean_data = stripslashes($clean_data); 
    
    // htmlspecialchars converte caracteres especiais em HTML entities, 
    // por exemplo: < e > converte para &lt; e &gt;
    $clean_data = htmlspecialchars($clean_data); 
    return $clean_data;
  }

?>
