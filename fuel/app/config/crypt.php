<?php

/**
 * 暗号鍵はリポジトリに固定値を置かず、環境変数から注入する
 *
 * 必須:
 * - FUEL_CRYPTO_KEY
 * - FUEL_CRYPTO_IV
 * - FUEL_CRYPTO_HMAC
 */

$crypto_key = getenv('FUEL_CRYPTO_KEY');
$crypto_iv = getenv('FUEL_CRYPTO_IV');
$crypto_hmac = getenv('FUEL_CRYPTO_HMAC');

if (empty($crypto_key) or empty($crypto_iv) or empty($crypto_hmac))
{
  throw new RuntimeException(
    '暗号鍵が未設定です。FUEL_CRYPTO_KEY / FUEL_CRYPTO_IV / FUEL_CRYPTO_HMAC を環境変数に設定してください。'
  );
}

return array(
  'crypto_key' => $crypto_key,
  'crypto_iv' => $crypto_iv,
  'crypto_hmac' => $crypto_hmac,
);
