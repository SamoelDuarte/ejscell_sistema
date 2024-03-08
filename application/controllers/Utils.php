<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Utils {

    public static function createPasswordHash($password, $salt)
    {
        $CI =& get_instance();
        $hashed = password_hash($password . $salt, PASSWORD_DEFAULT);
        return $hashed;
    }

    public static function passwordIsValid($typedPassword, $storedPassword, $salt)
    {
        return password_verify($typedPassword . $salt, $storedPassword);
    }

    public static function createPasswordSalt()
    {
        return md5(uniqid(rand(), true));
    }

    public static function createUserToken()
    {
        return md5(uniqid(rand(), true));
    }

    public static function formatProperPersonName($text)
    {
        // Implementação do método
    }

    public static function createTwoFactorCode()
    {
        return substr(str_shuffle('00112233445566778899'), 0, 6);
    }

    public static function formatProperUsername($str)
    {
        // Implementação do método
    }

    public static function prepareMoneyForDatabase($val)
    {
        return str_replace(",", ".", str_replace(".", "", $val));
    }

    public static function removeAccents($string)
    {
        // Implementação do método
    }

    public static function sanitizeZipcode($zipcode)
    {
        return str_replace(['.', '-', ' '], '', $zipcode);
    }

    public static function sanitizeCpfCnpj($doc)
    {
        return str_replace(['.', '-', '/', ' '], '', $doc);
    }

    public static function sanitizePhone($phone)
    {
        return str_replace(['.','+', '-', '/', '(', ')', ' '], '', $phone);
    }

    public static function sanitizeFee($fee)
    {
        return str_replace([','], '.', $fee);
    }

    public static function createCode()
    {
        return md5(uniqid(rand(), true));
    }

    public static function createResetPasswordToken()
    {
        return md5(uniqid(rand(), true));
    }

    public static function cpfIsValid($cpf)
    {
        // Implementação do método
    }

    public static function returnCep($cep)
    {
        // Implementação do método
    }

    public static function cnpjIsValid($cnpj)
    {
        // Implementação do método
    }

    public static function cpfCnpjAreValid($cpfCnpj)
    {
        // Implementação do método
    }

    public static function removeLineBreaks($str)
    {
        return str_replace(array("\r", "\n"), '', $str);
    }

    public static function sanitizeJid($string)
    {
        $novaString = str_replace(':23@s.whatsapp.net', '', $string);
        return $novaString; 
    }
}
