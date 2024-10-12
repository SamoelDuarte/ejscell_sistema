<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
MODIFICAR PARA ACESSO AO BANCO DE DADOS.
*/

$active_group = 'default';
$active_record = TRUE;

$db['default']['hostname'] = 'localhost'; //Endereço de servidor
$db['default']['username'] = 'root'; // usuário do banco de dados
$db['default']['password'] = ''; //Senha do banco de dados
$db['default']['database'] = 'ejs'; //Banco de dados
$db['default']['dbdriver'] = 'mysqli'; //Não auterar
$db['default']['dbprefix'] = 'ejs_'; //Não auterar
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;


/* End of file database.php */
/* Location: ./application/config/database.php */
