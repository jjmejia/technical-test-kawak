<?php

/**
 * Recupera valores almacenados en sesión de usuario
 *
 * @author John Mejia
 */

class UserSession
{
	private string $prefix = '';

	public function __construct(string $prefix = '')
	{
		// Inicia sesión si no ha sido iniciada previamente
		if (!isset($_SESSION)) {
			session_start();
		}
		// Registra prefijo a usar (si alguno)
		$this->prefix = trim($prefix);
		if ($this->prefix === '') {
			$this->prefix = 'localdata';
		}
	}

	/**
	 * Recupera datos de sesión.
	 */
	public function get(string $name, mixed $default = false)
	{
		if (
			!empty($_SESSION[$this->prefix]) &&
			is_array($_SESSION[$this->prefix]) &&
			array_key_exists($name, $_SESSION[$this->prefix])
		) {
			$default = $_SESSION[$this->prefix][$name];
		}

		return $default;
	}

	/**
	 * Guarda datos de sessión
	 */
	public function save(array $data)
	{
		foreach ($data as $k => $v) {
			$_SESSION[$this->prefix][$k] = $v;
		}
	}

	/**
	 * Elimina datos de sesión.
	 */
	public function delete(string $name, mixed $default = false): bool
	{
		if (
			!empty($_SESSION[$this->prefix]) &&
			is_array($_SESSION[$this->prefix]) &&
			array_key_exists($name, $_SESSION[$this->prefix])
		) {
			unset($_SESSION[$this->prefix][$name]);
			return true;
		}

		return false;
	}

	/**
	 * Token único para validar acciones delicadas.
	 */
	function csrf(bool $force_creation = false)
	{
		// Requiere PHP5 o superior (random_bytes)
		if ($force_creation || empty($_SESSION[$this->prefix]['csrf-token'])) {
			$_SESSION[$this->prefix]['csrf-token'] = str_replace('=', '', base64_encode(bin2hex(random_bytes(32))));
		}

		return $_SESSION[$this->prefix]['csrf-token'];
	}
}
