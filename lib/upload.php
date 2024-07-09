<?php

function enviarArquivo($error, $size, $name, $tmp_name) {

    if($error)
        die("Falha ao enviar arquivo");

    if($size > 2 * 1024 * 1024) // 2MB
        die("Arquivo muito grande!! Max: 2MB"); 

    $pasta = "tmp/";
    $nomeDoArquivo = $name;
    $novoNomeDoArquivo = uniqid();
    $extensao = strtolower(pathinfo($nomeDoArquivo, PATHINFO_EXTENSION));

    if($extensao != "jpg" && $extensao != 'png')
        die("Tipo de arquivo não aceito");

    $path = $pasta . $novoNomeDoArquivo . "." . $extensao;
    if (move_uploaded_file($tmp_name, $path))
        return $path;
    else
        return false;
}
?>