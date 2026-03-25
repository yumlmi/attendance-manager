<?php
/**
 * Development-only session settings.
 *
 * In local Docker environments, crypto keys may not be provided via env vars.
 * FuelPHP encrypts cookie sessions via `fuel/app/config/crypt.php`, so disable
 * cookie encryption in development to avoid RuntimeException.
 */
return array(
	'encrypt_cookie' => false,
);

