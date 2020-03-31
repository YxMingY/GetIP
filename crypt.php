<?php
/**
 * note: 以下函数生成的密文均被base64加密
 */
function aes256_encode(string $data,string $key):string
{
  $iv = openssl_random_pseudo_bytes(16, $isStrong);
  if (false === $iv && false === $isStrong) {
    die('IV generate failed');
  }
  return base64_encode(openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv).$iv);
}
function aes256_decode(string $data,string $key):string
{
  $data = base64_decode($data);
  $l = strlen($data);
  $iv = substr($data,$l-16);
  $crypted = substr($data,0,$l-16);
  return openssl_decrypt($crypted, 'aes-256-cbc', $key, 0, $iv);
}
/**
 * @param &$private_key
 * @param &$public_key
 * @param int $key_length -- 密钥长度
 * note: 待加密数据长度最大值为key_length-11
 * note: 所有key都被base64加密
 * @return bool -- 生成成功返回true
 */
function rsa_create_keys(&$private_key,&$public_key,int $key_length = 64):bool
{
  $config = array(
    "private_key_bits" => $key_length*8,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
    "config" => dirname(__FILE__).'/openssl.cnf'
  );
  if($res === false)
    return false;
  $res = openssl_pkey_new($config);
  openssl_pkey_export($res,$private_key,null,$config);
  $private_key = base64_encode($private_key);
  $public_key = openssl_pkey_get_details($res);
  $public_key = base64_encode($public_key["key"]);
  return true;
}
function rsa_private_encode(string $data,string $private_key):string
{
  openssl_private_encrypt($data,$code,base64_decode($private_key));
  return base64_encode($code);
}
function rsa_public_encode(string $data,string $public_key):string
{
  openssl_public_encrypt($data,$code,base64_decode($public_key));
  return base64_encode($code);
}
function rsa_private_decode(string $code,string $private_key):string
{
  openssl_private_decrypt(base64_decode($code), $data, base64_decode($private_key));
  return $data;
}
function rsa_public_decode(string $code,string $public_key):string
{
  openssl_public_decrypt(base64_decode($code), $data, base64_decode($public_key));
  return $data;
}
