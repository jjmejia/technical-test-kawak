<?php

/**
 * Soporte para la visualización de páginas Web.
 *
 * Clase editada de una librería previamente creada.
 *
 * Prueba técnica KAWAK
 *
 * @author John Mejía
 */

class WebSupport
{
	/**
	 * Incluye un archivo PHP en el contexto actual.
	 *
	 * El archivo especificado en $filename es incluido en el contexto actual,
	 * pudiendo acceder a las variables establecidas en $params.
	 *
	 * @param string $filename nombre del archivo a incluir (con path)
	 * @param array $params variables a pasar al archivo incluir
	 *
	 * @return mixed El valor de retorno del archivo incluido. Puede indicarse que
	 * 				 ha ocurrido un error retornando FALSE.
	 */
	protected function include($filename, $params = []): array
	{
		$fun = static function (string $view_filename, array &$view_args) {

			// Previene se invoque un archivo no valido
			if ($view_filename === '' || !is_file($view_filename)) {
				// Previene se muestre el root
				$info_filename = str_replace(
					str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '',
					str_replace('\\', '/', $view_filename)
				);
				throw new Exception("El archivo de vista indicado {$info_filename} no existe");
			}

			if (count($view_args) > 0) {
				// EXTR_SKIP previene use $filename o $args y genere colisión de valores.
				// Se extraen como valores referencia para evitar duplicados.
				extract($view_args, EXTR_SKIP | EXTR_REFS);
			}

			return require($view_filename);
		};

		$data = $fun($filename, $params);

		if (!is_array($data)) {
			$data = ['include_value' => $data];
		}

		return $data;
	}

	/**
	 * Visualiza una vista con los parámetros dados.
	 *
	 * @param string $viewname El nombre de la vista a visualizar. Debe ser un
	 * 						   archivo PHP existente en el directorio de vistas.
	 * @param array $view_params Parámetros a pasar a la vista.
	 *
	 * @return bool TRUE si la vista se mostró sin problemas.
	 *
	 * @throws Exception Si no se ha indicado nombre de la vista o si el archivo no existe.
	 */
	public function view(string $viewname, array $view_params = [])
	{
		$view_filename = '';
		$viewname = trim($viewname);
		if ($viewname !== '') {
			$view_filename = __DIR__ . '/../../web/vistas/' . $viewname . '.php';
		}

		ob_start();

		$this->include($view_filename, $view_params);

		// Captura resultado
		return ob_get_clean();
	}

	/**
	 * Visualiza una vista con los parámetros dados.
	 *
	 * Internamente utiliza un layout para mostrar el contenido.
	 *
	 * @param string $viewname El nombre de la vista a visualizar. Debe ser un
	 * 						   archivo PHP existente en el directorio de vistas.
	 * @param array $view_params Parámetros a pasar a la vista.
	 *
	 * @return bool TRUE si la vista se mostró sin problemas.
	 *
	 * @throws Exception Si no se ha indicado nombre de la vista o si el archivo no existe.
	 */
	public function showContent(string $viewname, array $view_params = [])
	{
		// Captura resultado
		$view_params['viewContents'] = $this->view($viewname, $view_params);

		// Invoca el layout
		echo $this->view('layout', $view_params);
	}

	/**
	 * Redirige a la URL especificada.
	 *
	 * Para casos donde el navegador no permita la redirección automática,
	 * se presenta un mensaje al usuario (que puede ser personalizado) y un enlace para continuar.
	 *
	 * @param string $url La URL a la que se redirigirá.
	 * @param array $wait_message (Opcional) Mensaje alternativo al usuario.
	 */
	public function reload(string $url, string $wait_message = '')
	{
		$html = '';

		if ($wait_message == '') {
			// Define mensaje automático
			$wait_message = 'Un momento, por favor, se está redireccionando a una nueva página.';
		}

		$url = completar_url($url);
		$reload_link = "<a href=\"{$url}\">{$url}</a>";
		if (!headers_sent()) {
			// header("HTTP/1.1 301 Moved Permanently"); <-- No debe marcarla como permanente!
			header("Location: {$url}");
		}
		$html .= "<script>window.location='{$url}';</script>";
		$html .= $wait_message .
			"<p>" .
			"Si la nueva página no carga automáticamente, " .
			"puede continuar usando el siguiente enlace: {$reload_link}" .
			"</p>";

		exit($html);
	}
}
