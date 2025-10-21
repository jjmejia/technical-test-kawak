<?php

/**
 * Recupera valores enviados por usuario vía Web
 *
 * @author John Mejia
 */

class Request
{

	private $ip_client = ''; // Previene repetir busquedas
	private $raw_input = '';
	private $raw_data = array();

	public function __construct()
	{
		$this->raw_input = file_get_contents('php://input');
		if (json_validate($this->raw_input)) {
			$this->raw_data = json_decode($this->raw_input, true);
		} else {
			parse_str($this->raw_input, $this->raw_data);
		}
	}

	/**
	 * Recupera valor de la variable global $_REQUEST.
	 */
	public function get(string $nombre, string $xdefecto = ''): string
	{
		// Valida si el parámetro solicitado existe
		if (array_key_exists($nombre, $_REQUEST) && is_string($_REQUEST[$nombre])) {
			return trim($_REQUEST[$nombre]);
		}
		return $xdefecto;
	}

	/**
	 * Recupera datos recibidos en el body de la consulta (sea del tipo Json o URLEncode).
	 * Si no hay datos recibidos en el body, busca en $_REQUEST.
	 */
	public function getBody(string $nombre, string $xdefecto = ''): string
	{
		// Valida si el parámetro solicitado existe
		if ($this->raw_input !== '') {
			if (is_array($this->raw_data) && array_key_exists($nombre, $this->raw_data) && is_string($this->raw_data[$nombre])) {
				return trim($this->raw_data[$nombre]);
			}
			return $xdefecto;
		}
		// Como alternativa si no hay datos recibidos en el body, lo busca en $_REQUEST
		return $this->get($nombre, $xdefecto);
	}

	/**
	 * Recupera valor tipo booleano.
	 */
	public function getBoolean(string $nombre): bool
	{
		$valor = strtolower($this->get($nombre, ''));
		if ($valor === '' || $valor === '0' || $valor === 'false' || $valor === 'falso') {
			return false;
		}
		return true;
	}

	/**
	 * Dirección IP del cliente remoto.
	 *
	 * Para consultas de consola retorna "cli".
	 *
	 * @return string Dirección IP del cliente remoto.
	 */
	public function getIpCliente(): string
	{
		if ($this->ip_client === '') {
			// HTTP_X_FORWARDED_FOR:
			// Usado en vez de REMOTE_ADDR cuando se consulta detrás de un proxy server.
			// Puede contener múltiples IPs de proxies por los que se ha pasado.
			// Solamente la IP del último proxy (última IP de la lista) es de fiar.
			// ( https://stackoverflow.com/questions/11452938/how-to-use-http-x-forwarded-for-properly )
			// Si no se emplean proxys para la consulta, retorna vacio.
			// REMOTE_ADDR:
			// La dirección IP desde donde el usuario está viendo la página actual.
			// HTTP_CLIENT_IP:
			// Opcional para algunos servidores Web en remplazo de REMOTE_ADDR.
			$options = ['HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR', 'HTTP_CLIENT_IP'];
			foreach ($options as $name) {
				if (empty($_SERVER[$name])) {
					continue;
				}
				$this->ip_client = trim($_SERVER[$name]);
				// Para HTTP_X_FORWARDED_FOR puede encontrar multiples valores separados por
				// comas. Sólo el último valor es relevante.
				if (strpos($this->ip_client, ',') !== false) {
					$proxy_list = explode(",", $this->ip_client);
					$this->ip_client = trim(array_pop($proxy_list));
				}
				if ($this->ip_client !== '') {
					break;
				}
			}
			// En caso que retorne un nombre (como "localhost") se asegura esté en
			// minusculas para facilitar comparaciones.
			$this->ip_client = strtolower($this->ip_client);
			// IPv4, IPv6, Associative name, Consola
			if (in_array($this->ip_client, ['127.0.0.1', '::1', 'localhost'])) {
				// Estandariza resultado
				$this->ip_client = 'localhost';
			}
		}

		return $this->ip_client;
	}
}
