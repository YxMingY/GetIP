<?php
function encrypt(string $key,string $data):string
{
  $iv = openssl_random_pseudo_bytes(16, $isStrong);
  if (false === $iv && false === $isStrong) {
    die('IV generate failed');
  }
  return openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv).$iv;
}
function decrypt(string $key,string $data):string
{
  $l = strlen($data);
  $iv = substr($data,$l-16);
  $crypted = substr($data,0,$l-16);
  return openssl_decrypt($crypted, 'aes-256-cbc', $key, 0, $iv);
}
