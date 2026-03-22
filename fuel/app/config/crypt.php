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

$validate_crypto_value = function ($name, $value)
{
  // FuelPHP Crypt は URL-safe base64（= なし）を前提とする
  if (preg_match('/^[A-Za-z0-9_-]+$/', $value) !== 1)
  {
    throw new RuntimeException($name.' の形式が不正です。base64url（A-Z a-z 0-9 _ -）で設定してください。');
  }

  // FuelPHP の内部実装上、キー文字列長は 4 の倍数が前提
  if ((strlen($value) % 4) !== 0)
  {
    throw new RuntimeException($name.' の長さが不正です。文字数を4の倍数にしてください。');
  }
};

$validate_crypto_value('FUEL_CRYPTO_KEY', $crypto_key);
$validate_crypto_value('FUEL_CRYPTO_IV', $crypto_iv);
$validate_crypto_value('FUEL_CRYPTO_HMAC', $crypto_hmac);

return array(
  'crypto_key' => $crypto_key,
  'crypto_iv' => $crypto_iv,
  'crypto_hmac' => $crypto_hmac,
);
