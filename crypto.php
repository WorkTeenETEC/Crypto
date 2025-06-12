<?php

if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    // Tentou acessar diretamente pela URL
    header("HTTP/1.0 403 Forbidden");
    echo "🚫 Acesso negado.";
    echo "<br>";
    echo "<br>";
    echo "Redirecionando para a página inicial...";
    echo "<br>";
    echo "<br>";
    header("Refresh: 3; URL=../inicio-sem-login.php");
    exit;
}

function gerarChave() {
    // Chave fixa para criptografia (deve ter 32 bytes para AES-256)
    return hash('sha256', 'S@aS3n6a', true);
}

function criptografar($dados) {
    $chave = gerarChave();
    $iv = openssl_random_pseudo_bytes(16); // IV de 16 bytes para AES-256-CBC
    $criptografado = openssl_encrypt($dados, 'AES-256-CBC', $chave, 0, $iv);

    // Codifica o texto criptografado e o IV em base64, separando por '::'
    return base64_encode($criptografado) . '::' . base64_encode($iv);
}

function descriptografar($dados) {
    $chave = gerarChave();

    // Divide a string criptografada nas duas partes
    $partes = explode('::', $dados);

    if (count($partes) !== 2) {
        // Se a string não tiver as duas partes esperadas, retorna false
        return false;
    }

    list($criptografado_b64, $iv_b64) = $partes;

    // Decodifica de base64
    $criptografado = base64_decode($criptografado_b64);
    $iv = base64_decode($iv_b64);

    // Descriptografa usando a chave e IV
    return openssl_decrypt($criptografado, 'AES-256-CBC', $chave, 0, $iv);
}

?>
