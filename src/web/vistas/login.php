<style>
	body {
		display: flex;
		justify-content: center;
		align-items: center;
		height: 100vh;
	}

	.login-container {
		background: white;
		padding: 2rem;
		border-radius: 8px;
		box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
		width: 90%;
		max-width: 400px;
	}

	.login-container h2 {
		margin-bottom: 1.5rem;
		color: var(--color-primario);
		text-align: center;
	}

	.form-group {
		margin-bottom: 1rem;
	}

	.form-group label {
		display: block;
		margin-bottom: 0.5rem;
	}

	.form-group input {
		width: 100%;
		padding: 0.75rem;
		border: 1px solid #ccc;
		border-radius: 4px;
		font-size: 1rem;
	}

	.form-group input:focus {
		border-color: var(--color-secundario);
		outline: none;
	}

	.btn {
		width: 100%;
		padding: 0.75rem;
		background-color: var(--color-secundario);
		border: none;
		border-radius: 4px;
		color: white;
		font-size: 1rem;
		cursor: pointer;
	}

	.btn:hover {
		background-color: var(--color-primario);
	}

	.error {
		color: red;
		font-size: 0.9rem;
		display: none;
		margin-top: 0.5rem;
	}
</style>

<div class="login-container">
	<h2>Iniciar sesión</h2>
	<form id="loginForm" action="<?= completar_url("registrar") ?>" method="post">
		<div class="form-group">
			<label for="username">Usuario</label>
			<input type="text" id="username" name="username" required>
		</div>
		<div class="form-group">
			<label for="password">Contraseña</label>
			<input type="password" id="password" name="password" required>
		</div>
		<button type="submit" class="btn">Entrar</button>
		<div class="error" id="errorMsg"><?= $mensaje ?></div>
	</form>
</div>

<script>
	if (document.getElementById('errorMsg').html !== '') {
		document.getElementById('errorMsg').style.display = 'block';
	}
	/*document.getElementById('loginForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const user = document.getElementById('username').value.trim();
      const pass = document.getElementById('password').value.trim();

      // Ejemplo de validación simple (reemplaza con lógica del servidor)
      if (user === '' || pass === '') {
        document.getElementById('errorMsg').style.display = 'block';
      }
	  // Procesa resultado
	  e.submit();
    });*/
</script>