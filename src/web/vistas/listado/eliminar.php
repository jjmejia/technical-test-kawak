<?php

/**
 * Prueba técnica KAWAK
 *
 * @author John Mejía
 */

$request = new Request();
$web = new WebSupport();
$session = obtener_session();
$bdd = obtener_bdd();

// Recupera id del registro a editar (al adicionar toma valor "0")
$doc_id = $request->getNumber('doc_id');
$docs = [];

$docs = $bdd->bddPrimerRegistro(
	"SELECT
	doc_id, doc_nombre, doc_codigo, doc_contenido,
	tip_id,
	pro_id
	FROM doc_documento
	LEFT JOIN tip_tipo_doc ON (tip_id = doc_id_tipo)
	LEFT JOIN pro_proceso ON (pro_id = doc_id_proceso)
	WHERE doc_id = ?
	ORDER BY doc_id desc",
		[$doc_id]
	);

if (!is_array($docs) || count($docs) <= 0) {
	throw new Exception("Registro a eliminar no fue encontrado ($doc_id)");
}

$message = 'No fue posible eliminar el documento.';
$success = $bdd->bddRemover('doc_documento', [$doc_id], 'doc_id');
if ($success) {
	$message = 'Documento eliminado correctamente.';
}
$session->saveReloadParams(['message' => $message]);
$web->reload('listado');