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
	 * Obtiene el valor de una variable de sesión.
	 *
	 * @param string $name El nombre de la variable de sesión a obtener.
	 * @param mixed $default Valor por defecto a retornar si la variable no existe. Por defecto es false.
	 * @return mixed El valor de la variable de sesión si existe, o el valor por defecto si no.
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
	 * Guarda los datos de la sesión del usuario.
	 *
	 * @param array $data Arreglo asociativo que contiene los datos a guardar en la sesión.
	 */
	public function save(array $data)
	{
		foreach ($data as $k => $v) {
			$_SESSION[$this->prefix][$k] = $v;
		}
	}

	/**
	 * Elimina una variable de sesión con el nombre especificado.
	 *
	 * @param string $name El nombre de la variable de sesión a eliminar.
	 * @return bool Devuelve true si la variable de sesión fue eliminada exitosamente,
	 *              o false si no se pudo eliminar o no existe.
	 */
	public function delete(string $name): bool
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
	 * Genera y gestiona el token CSRF (Cross-Site Request Forgery) para la sesión del usuario.
	 *
	 * @param bool $force_creation Indica si se debe forzar la creación de un nuevo token CSRF.
	 *                             Si es true, se generará un nuevo token incluso si ya existe uno.
	 *                             Si es false, se utilizará el token existente si está disponible
	 *                             o crea uno nuevo si no existe previamente.
	 *
	 * @return string El token CSRF generado o existente.
	 */
	function csrf(bool $force_creation = false)
	{
		// Requiere PHP5 o superior (random_bytes)
		if ($force_creation || empty($_SESSION[$this->prefix]['csrf-token'])) {
			$_SESSION[$this->prefix]['csrf-token'] = str_replace('=', '', base64_encode(bin2hex(random_bytes(32))));
		}

		//  Retorna token único para validar acciones delicadas.
		return $_SESSION[$this->prefix]['csrf-token'];
	}

	/**
	 * Guarda los parámetros de recarga para la sesión del usuario.
	 *
	 * @param array $data Un arreglo asociativo que contiene los parámetros de recarga a guardar.
	 *                    La estructura y las claves esperadas de este arreglo deben definirse
	 *                    según los requisitos específicos de la aplicación.
	 */
	public function saveReloadParams(array $data)
	{
		$this->save(['reloadParams' => $data]);
	}

	/**
	 * Recupera el valor de un parámetro de recarga desde la sesión.
	 *
	 * @param string $name El nombre del parámetro a recuperar.
	 * @param mixed $default El valor predeterminado a devolver si no se encuentra el parámetro. Por defecto es una cadena vacía.
	 * @return mixed El valor del parámetro si existe, o el valor predeterminado en caso contrario.
	 */
	public function getReloadParam(string $name, mixed $default = '')
	{
		$params = $this->get('reloadParams', false);
		if (!empty($params[$name])) {
			$default = $params[$name];
		}

		return $default;
	}

	/**
	 * Elimina los parámetros de recarga de la sesión del usuario.
	 *
	 * @return bool Devuelve true si los parámetros de recarga fueron eliminados exitosamente, false en caso contrario.
	 */
	public function removeReloadParams(): bool
	{
		return $this->delete('reloadParams');
	}
}
