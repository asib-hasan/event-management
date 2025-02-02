<?php
class BaseController {
    protected function checkLoginStatus() {
        if (isset($_SESSION['user'])) {
            return true;
        }
        return false;
    }

    protected function encrypt_decrypt($action, $string) {
        $output = false;
        $encrypt_method = 'AES-128-CBC';
        $secret_key = 'okzHbztRQNnWOIDGGYbgVMCd4XRdtVkKR';
        $secret_iv = '12345678901122';
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } elseif ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }
}
?>