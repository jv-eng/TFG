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

<div align="left"><a href="https://www.upm.es/" target="_blank"><img src="imagenes/logo_copia.png"></a></div>

<div align="right"><a href="https://www.fi.upm.es/" target="_blank"><img src="imagenes/logo_etsiinf_transparente.png"></a></div>

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

		$query = $con->prepare("SELECT * FROM `profesor` WHERE`mail` = ? AND `Validado` = '1';");
		mysqli_stmt_bind_param($query, "s",  $_POST["mail"]);
		mysqli_stmt_execute($query);
		$result = mysqli_stmt_get_result($query);
		mysqli_stmt_close($query);

		if ($result) {
			if ($result->num_rows > 0) {
				foreach ($result as $row) {
					$password_guardada = $row["password"];
					$id = $row["id_profesor"];
				}

				$password_encript = md5($_POST["password"]);
				if (strcmp($password_guardada, $password_encript) !== 0) {
					echo "<b><big>El correo o la contraseña introducidos son incorrectos.</big></b>";
					$next_page = "Inicio.php";
				} else {
					if ($row["Administrador"]) {
						$next_page = "Menu_admin.php";
						echo "<b><big>Login realizado correctamente como administrador.</big></b> </br>";
						setcookie("mail", $_POST["mail"], time() + 3600);	//Crear cookie
						$id_sesion = md5($_POST["mail"] . "" . $_SERVER['REMOTE_ADDR']);
						$time = time();
						$time_click = $time + 3600;
						setcookie("id_sesion", $id_sesion, $time_click);	//Crear cookie

						$query = $con->prepare("INSERT INTO `session` (`id_sesion`, `mail_profesor`, `mail_alumno`, `hora_inicio`, `hora_click`, `hora_fin`, `profesor`) 
							VALUES (?, ?, ?, ?, ?, ?, '1');");
						mysqli_stmt_bind_param($query, "issiii", $id_sesion, $_POST["mail"],$_POST["mail"],$time,$time,$time_click);

					} else {
						$next_page = "Profesor_menu.php";
						echo "<b><big>Login realizado correctamente como profesor.</big></b>";
						setcookie("mail", $_POST["mail"], time() + 3600);	//Crear cookie
						$id_sesion = md5($_POST["mail"] . "" . $_SERVER['REMOTE_ADDR']);
						$time = time();
						$time_click = $time + 3600;
						setcookie("id_sesion", $id_sesion, $time_click);	//Crear cookie
	
						$query = $con->prepare("INSERT INTO `session` (`id_sesion`, `mail_profesor`, `mail_alumno`, `hora_inicio`, `hora_click`, `hora_fin`, `profesor`) 
							VALUES (?, ?, NULL, ?, ?, ?, '1');");
						mysqli_stmt_bind_param($query, "isiii", $id_sesion, $_POST["mail"],$time,$time,$time_click);

					try {
						mysqli_stmt_execute($query);
						$result = mysqli_stmt_get_result($query);
						mysqli_stmt_close($query);
					} catch (Exception $e) {
						$next_page = "Inicio.php";
						setcookie("mail",$_POST["mail"], strtotime('-1 day'));	//Crear cookie
						setcookie("id_sesion",$id_sesion, strtotime('-1 day'));	//Crear cookie
					}
				}
			} else {

				$query = $con->prepare("SELECT * FROM `alumno` WHERE (`mail_alumno` = ?);");
				mysqli_stmt_bind_param($query, "s", $_POST["mail"]);
				mysqli_stmt_execute($query);
				$result = mysqli_stmt_get_result($query);
				mysqli_stmt_close($query);

				if ($result && mysqli_num_rows($result) > 0) {
					foreach ($result as $row) {
						$password_guardada = $row["password"];
						$id = $row["idalumno"];
					}

					$password_encript = md5($_POST["password"]);
					if (strcmp($password_guardada, $password_encript) !== 0) {
						echo "<b><big>El correo o la contraseña introducidos son incorrectos.</big></b>";
						$next_page = "Inicio.php";
					} else {
						$next_page = "Alumno_Menu.php";
						echo "<b><big>Login realizado correctamente como alumno.</big></b>";
						setcookie("mail", $_POST["mail"], time() + 3600);	//Crear cookie
						$id_sesion = md5($_POST["mail"] . "" . $_SERVER['REMOTE_ADDR']);
						$time = time();
						$time_click = $time + 3600;
						setcookie("id_sesion", $id_sesion, $time_click);	//Crear cookie

						$query = $con->prepare("INSERT INTO `session` (`id_sesion`, `mail_profesor`, `mail_alumno`, `hora_inicio`, `hora_click`, `hora_fin`, `profesor`) 
							VALUES (?, null, ?, ?, ?, ?, '1');");
						mysqli_stmt_bind_param($query, "isiii", $id_sesion, $_POST["mail"],$time,$time,$time_click);
						try {
							mysqli_stmt_execute($query);
							$result = mysqli_stmt_get_result($query);
							mysqli_stmt_close($query);
						} catch (Exception $e) {
							$next_page = "Inicio.php";
							setcookie("mail",$_POST["mail"], strtotime('-1 day'));	//Crear cookie
							setcookie("id_sesion",$id_sesion, strtotime('-1 day'));	//Crear cookie
						}
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