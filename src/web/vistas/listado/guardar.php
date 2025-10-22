<?php

/**
 * Prueba técnica KAWAK
 * Guarda datos asociados a un documento nuevo o uno existente.
 * Descargo: Creado con asistencia de ChatGPT.
 *
 * @author John Mejía
 */

$request = new Request();
$bdd = obtener_bdd();

// Indicar que se envía y recibe JSON
header('Content-Type: application/json; charset=utf-8');

// Valores esperados (llave => valor por defecto[:nombre en bdd[:titulo[:longitud]]])
$data_request = [
	'nombre' => ':doc_nombre:El nombre del documento:60',
	'proceso' => '0:doc_id_proceso',
	'tipo' => '0:doc_id_tipo',
	'contenido' => ':doc_contenido:El texto que detalla el Contenido del documento:4000',
	'doc_id' => 0,
	'token' => ''
];

// Datos a recuperar:
$data_bdd = []; 	// Elementos para BDD
$doc_id = 0; 		// ID del registro afectado (si alguno)
$token_user = ''; 	// Token de autorización

// Datos de validación de errores
$error_message = [];
$full_data = true;

foreach ($data_request as $key => $value) {
	// Descompone elementos
	$data = [
		'default' => '',
		'name' => '',
		'title' => $key,
		'len' => 0
	];

	$pos = strpos($value, ':');
	if ($pos !== false) {
		// Remueve llave original para adicionar nueva sin la longitud
		$data_param = explode(':', $value);
		$data['value'] = $data_param[0];
		if (isset($data_param[1])) {
			$data['name'] = $data_param[1];
		}
		if (isset($data_param[2])) {
			$data['title'] = $data_param[2];
		}
		if (isset($data_param[3])) {
			$data['len'] = $data_param[3];
		}
	} else {
		$data['value'] = $value;
	}

	// Validar datos recibidos (solamente "doc_id" puede ser cero)
	$value = $request->getBody($key, $data['value']);
	if (empty($value) && $key !== 'doc_id') {
		$full_data = false;
		$error_message['nodata'] = "Datos incompletos o inválidos($key).";
	}

	// Valida longitud (si aplica)
	if ($data['len'] > 0 && strlen($value) > $data['len']) {
		$full_data = false;
		if (!empty($data['title'])) {
			$error_message[$key] = "{$data['title']} no debe exceder los {$data['len']} carácteres.";
		}
	}

	// Actualiza datos a guardar en bdd
	if (!empty($data['name'])) {
		$data_bdd[$data['name']] = $value;
	}

	if ($key === 'doc_id') {
		$doc_id = $value;
	} elseif ($key === 'token') {
		$token_user = $value;
	}
}

// Valida autorización por token
if ($full_data && !csrf_check_token($token_user)) {
	$full_data = false;
	$error_message['notoken'] = "El token de autorización recibido no es válido o ya fue usado. Recargue la página e intente de nuevo.";
}

if (!$full_data) {
	json_response(400, implode('\\n', $error_message));
}

$success = false;
// Realiza inserción/actualización
if ($doc_id > 0) {
	// Recupera valores para comparar si hay cambios a realizar
	$docs = $bdd->bddPrimerRegistro(
		"SELECT
	doc_id, doc_nombre, doc_codigo, doc_contenido,
	doc_id_tipo,
	doc_id_proceso
	FROM doc_documento
	WHERE doc_id = ?
	ORDER BY doc_id desc",
		[$doc_id]
	);

	if (empty($docs)) {
		json_response(400, "Registro a modificar no fue encontrado ($doc_id)");
	}

	$success_message = "Documento guardado correctamente.";
	$update_required = false;
	foreach ($data_bdd as $key => $value) {
		if ($value != $docs[$key]) {
			$update_required = true;
			break;
		}
	}
	if (!$update_required) {
		$success = true;
		$success_message = "Actualización no requerida.";
	} else {
		$success = $bdd->bddEditar('doc_documento', $data_bdd, $doc_id, 'doc_id');
	}
} else {
	$success = $bdd->bddAdicionar('doc_documento', $data_bdd);
	// json_response(400, $success . ' // ' . implode(':', $data_bdd));
	if ($success) {
		$success_message = "Nuevo Documento guardado correctamente.";
	}
}

if ($success) {
	// Actualiza CSRF
	$session = obtener_session();
	$token = $session->csrf(true);
	// Preserva mensaje
	$session->save(['reloadParams' => ['message' => $success_message]]);
	json_response(200, $success_message);
} else {
	// Valida si ya existe un registro con el nombre, tipo y proceso actuales
	$docs = $bdd->bddPrimerRegistro(
		"SELECT
	doc_id
	FROM doc_documento
	WHERE doc_id != ? AND
	doc_nombre = ? AND
	doc_id_tipo = ? AND
	doc_id_proceso = ?
	ORDER BY doc_id desc",
		[
			$doc_id,
			$data_bdd['doc_nombre'],
			$data_bdd['doc_id_tipo'],
			$data_bdd['doc_id_proceso']
		]
	);
	if (!empty($docs['doc_id'])) {
		json_response(500, "El nombre del documento ya existe para el tipo y proceso indicados. {$docs['doc_id']}");
	}

	// Error desconocido
	json_response(500, "Error al guardar el registro en la base de datos.");
}

// Previene se invoque el "layout"
exit;

// Función de soporte
function json_response(int $code, string $message)
{
	$status = 'ok';
	if ($code !== 200) {
		$status = 'error';
	}

	http_response_code($code);
	echo json_encode([
		"estado" => $status,
		"mensaje" => $message
	]);
	exit;
}
