<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>recibir_login_general</title>
	<link rel="stylesheet" href="./style.css">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1,maximun-sacale=1,munimun-sacale=1">

</head>

<script>
	function todayDate() {
		var d = new Date();
		var n = d.getFullYear() + "  ";
		return document.getElementById("date").innerHTML = n;
	}
</script>

<body onload="todayDate()">

	<header>

		<a href="https://www.upm.es/" target="_blank"><img src="imagenes/Logo_UPM.png"></a>
		<a href="https://www.fi.upm.es/" target="_blank"><img src="imagenes/logo_etsiinf_transparente.png"></a>


	</header>

	<div class="titulo">
		<h1>Sistema de reserva de tutorías</h1>
	</div>

	<div class="main-container">
		<?php
		$next_page = "";
		$con = mysqli_connect('localhost', 'root', '', 'prueba2_tfg_tutorias');
		if (!$con) {
			echo "Error: No se pudo conectar a la Base de Datos." . PHP_EOL;
			echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
			echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
			exit;
		}

		$sql = "SELECT * FROM `profesor` WHERE`mail` = '" . $_POST["mail"] . "' AND `Validado` = '1';";
		$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
		if ($result) {
			if ($result->num_rows > 0) {
				foreach ($con->query($sql) as $row) {
					$password_guardada = $row["password"];
					$id = $row["id_profesor"];
				}

				$password_encript = md5($_POST["password"]);
				if (strcmp($password_guardada, $password_encript) !== 0) {
					echo "<b><big>El correo o la contraseña introducidos son incorrectos.</big></b>";
					$next_page = "Login.php";
				} else {
					if ($row["Administrador"]) {
						$next_page = "Menu_admin.php";
						echo "<b><big>Login realizado correctamente como administrador.</big></b> </br>";
						setcookie("mail", $_POST["mail"], time() + 3600);	//Crear cookie
						$id_sesion = md5($_POST["mail"] . "" . $_SERVER['REMOTE_ADDR']);
						$time = time();
						$time_click = $time + 3600;
						setcookie("id_sesion", $id_sesion, $time_click);	//Crear cookie
						$sql = "INSERT INTO `session` (`id_sesion`, `mail_profesor`, `mail_alumno`, `hora_inicio`, `hora_click`, `hora_fin`, `profesor`) 
					VALUES ('" . $id_sesion . "', '" . $_POST["mail"] . "', '" . $_POST["mail"] . "', '" . $time . "', '" . $time . "', '" . $time_click . "', '1');";
						$result = mysqli_query($con, $sql) or die('Error en la inserción de la sesión1');
					} else {
						$next_page = "Profesor_menu.php";
						echo "<b><big>Login realizado correctamente como profesor.</big></b>";
						setcookie("mail", $_POST["mail"], time() + 3600);	//Crear cookie
						$id_sesion = md5($_POST["mail"] . "" . $_SERVER['REMOTE_ADDR']);
						$time = time();
						$time_click = $time + 3600;
						setcookie("id_sesion", $id_sesion, $time_click);	//Crear cookie
						$sql = "INSERT INTO `session` (`id_sesion`, `mail_profesor`, `mail_alumno`, `hora_inicio`, `hora_click`, `hora_fin`, `profesor`) 
					VALUES ('" . $id_sesion . "', '" . $_POST["mail"] . "', NULL, '" . $time . "', '" . $time . "', '" . $time_click . "', '1');";
						$result = mysqli_query($con, $sql) or die('Error en la inserción de la sesión2');
					}
				}
			} else {

				$sql2 = "SELECT * FROM `alumno` WHERE (`mail_alumno` = '" . $_POST["mail"] . "');";
				$result = mysqli_query($con, $sql2) or die('Error en la consulta a la BDD');
				if ($result && $result->num_rows > 0) {
					foreach ($con->query($sql2) as $row) {
						$password_guardada = $row["password"];
						$id = $row["idalumno"];
					}

					$password_encript = md5($_POST["password"]);
					if (strcmp($password_guardada, $password_encript) !== 0) {
						echo "<b><big>El correo o la contraseña introducidos son incorrectos.</big></b>";
						$next_page = "Login.php";
					} else {
						$next_page = "Alumno_Menu.php";
						echo "<b><big>Login realizado correctamente como alumno.</big></b>";
						setcookie("mail", $_POST["mail"], time() + 3600);	//Crear cookie
						$id_sesion = md5($_POST["mail"] . "" . $_SERVER['REMOTE_ADDR']);
						$time = time();
						$time_click = $time + 3600;
						setcookie("id_sesion", $id_sesion, $time_click);	//Crear cookie
						$sql = "INSERT INTO `session` (`id_sesion`, `mail_profesor`, `mail_alumno`, `hora_inicio`, `hora_click`, `hora_fin`, `profesor`) 
					VALUES ('" . $id_sesion . "', NULL,'" . $_POST["mail"] . "', '" . $time . "', '" . $time . "', '" . $time_click . "', '0');";
						$result = mysqli_query($con, $sql) or die('Error en la inserción de la sesión3');
					}
				} else {

					echo "<b><big>El correo introducido no está registrado en el Sistema o aún no está validado.</big></b>";
					$next_page = "Login.php";
					$id = "";
				}
			}
		} else {

		?>
			<p class="generalseparator red"><b>El correo introducido no está registrado en el Sistema.</b></p>;

		<?php

			$next_page = "Login.php";
			$id = "";
		}
		mysqli_close($con);
		?>
	</div>

	<form id="nextpage" action=<?php echo $next_page ?> method="POST">
		<input type="hidden" name="mail" value=<?php echo $_POST["mail"] ?>>
		<input type="hidden" name="id" value=<?php echo $id ?>>
		<input type="submit" name="Continuar" value="Continuar" style='display:none'>
	</form>

	<script type="text/javascript">
		document.getElementById('nextpage').submit();
	</script>

	<footer>
		&copy; <em id="date"></em> UPM
	</footer>
</body>

</html>