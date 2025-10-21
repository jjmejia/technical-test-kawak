<?php

/**
 * Prueba técnica KAWAK
 *
 * @author John Mejía
 */

$bdd = obtener_bdd();

$docs = $bdd->bddQuery(
	'SELECT
	doc_id, doc_nombre, doc_codigo, doc_contenido,
	tip_nombre, tip_prefijo,
	pro_nombre, pro_prefijo
	FROM doc_documento
	LEFT JOIN tip_tipo_doc ON (tip_id = doc_id_tipo)
	LEFT JOIN pro_proceso ON (pro_id = doc_id_proceso)
	ORDER BY doc_id desc');

?>
<style>
	header {
		background-color: var(--color-primario);
		color: white;
		padding: 1rem;
		display: flex;
		justify-content: space-between;
		align-items: center;
		flex-wrap: wrap;
	}

	header h1 {
		margin: 0;
		font-size: 1.5rem;
	}

	.user-info {
		font-size: 0.9rem;
	}

	.user-info a {
		color: var(--color-secundario);
		text-decoration: none;
		margin-left: 10px;
	}

	main {
		padding: 1rem;
		max-width: 900px;
		margin: auto;
	}

	table {
		width: 100%;
		border-collapse: collapse;
		background: white;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
		border-radius: 6px;
		overflow: hidden;
	}

	th,
	td {
		text-align: left;
		padding: 0.75rem;
		border-bottom: 1px solid #ddd;
	}

	th {
		background-color: var(--color-primario);
		color: white;
	}

	tr:hover {
		background-color: #f1f7ff;
	}

	button {
		background-color: var(--color-secundario);
		color: white;
		border: none;
		padding: 0.5rem 0.75rem;
		border-radius: 4px;
		cursor: pointer;
		font-size: 0.9rem;
	}

	button:hover {
		background-color: var(--color-primario);
	}

	.add-btn {
		margin: 1rem 0;
		display: inline-block;
	}

	    .search-bar {
      display: flex;
      justify-content: center;
      margin-bottom: 1.5rem;
      gap: 0.5rem;
    }

    .search-bar input {
      padding: 0.5rem;
      border: 1px solid #ccc;
      border-radius: 4px;
      width: 70%;
      font-size: 1rem;
    }

    .search-bar button {
      padding: 0.5rem 1rem;
      background-color: var(--color-secundario);
      border: none;
      border-radius: 4px;
      color: white;
      cursor: pointer;
    }

    .search-bar button:hover {
      background-color: var(--color-primario);
    }

	@media (max-width: 600px) {

		table,
		thead,
		tbody,
		th,
		td,
		tr {
			display: block;
		}

		th {
			display: none;
		}

		td {
			position: relative;
			padding-left: 50%;
			text-align: right;
		}

		td::before {
			position: absolute;
			left: 1rem;
			top: 0.75rem;
			white-space: nowrap;
			font-weight: bold;
			color: var(--color-primario);
		}

		td:nth-of-type(1)::before {
			content: "ID";
		}

		td:nth-of-type(2)::before {
			content: "Nombre";
		}

		td:nth-of-type(3)::before {
			content: "Correo";
		}

		td:nth-of-type(4)::before {
			content: "Rol";
		}

		td:nth-of-type(5)::before {
			content: "Acciones";
		}
	}
</style>

<main>
	<button class="add-btn" onclick="agregarRegistro()">+ Agregar nuevo</button>

	<div class="search-bar">
      <input type="text" id="buscar" placeholder="Buscar registro por nombre...">
      <button onclick="buscarRegistro()">Buscar</button>
    </div>

	<table id="tablaDatos">
		<thead>
			<tr>
				<th>ID</th>
				<th>Documento</th>
				<th>Proceso</th>
				<th>Tipo</th>
				<th>Código</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($docs as $data) {
				print_r($data); echo "<hr>";
			?>
			<tr>
				<td>1</td>
				<td>Juan Pérez</td>
				<td>juan@ejemplo.com</td>
				<td>Administrador</td>
				<td>Administrador</td>
				<td>
					<button onclick="editarRegistro(this)">Editar</button>
					<button onclick="eliminarRegistro(this)">Eliminar</button>
				</td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>
</main>

<form action="" method="post" id="formAccion">
<input type="hidden" name="doc_id" id="doc_id" value="">
<?= csrf_input_token() ?>
</form>

<script>
	function editarRegistro(btn) {
		const fila = btn.closest("tr");
		const nombre = fila.children[1].textContent;
		alert("Editar registro de: " + nombre);
	}

	function eliminarRegistro(btn) {
		const fila = btn.closest("tr");
		const nombre = fila.children[1].textContent;
		if (confirm("¿Eliminar a " + nombre + "?")) fila.remove();
	}

	function agregarRegistro() {
		const tabla = document.querySelector("#tablaDatos tbody");
		const nuevoId = tabla.rows.length + 1;
		const fila = document.createElement("tr");
		fila.innerHTML = `
        <td>${nuevoId}</td>
        <td>Nuevo Usuario</td>
        <td>nuevo@correo.com</td>
        <td>Usuario</td>
        <td>
          <button onclick="editarRegistro(this)">Editar</button>
          <button onclick="eliminarRegistro(this)">Eliminar</button>
        </td>`;
		tabla.appendChild(fila);
	}
</script>