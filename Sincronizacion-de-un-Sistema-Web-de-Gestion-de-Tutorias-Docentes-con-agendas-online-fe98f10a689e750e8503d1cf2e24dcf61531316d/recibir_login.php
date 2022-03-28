<!--	Author: Juan Borrero Carrón		-->

<head>
	<title>recibir_login</title>
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
		/*
				Funcionalidad por desarrollar:
				1. Recuperar de BDD la contraseña asociada al correo introducido. Buscar en la tabla que corresponda en función de si ha marcado profesor o no. 
				2. Comprar la contraseña guardada con la introducida en el formulario.
				Si son iguales:
					3A. Pasar al menú que corresponda (alumnos o profesores)
				Si son diferentes:
					3B. Mensaje de error y para atrás. 
			*/
		$logeado_profesor = false;
		$logeado_alumno = false;
		$con = mysqli_connect('localhost', 'root', '', 'prueba2_tfg_tutorias');
		if (!$con) {
			echo "Error: No se pudo conectar a la Base de Datos." . PHP_EOL;
			echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
			echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
			exit;
		}
		if ($_POST["pa"] == "p") {			//PROFESOR
			$sql = "SELECT password FROM `profesor` WHERE (`mail` = '" . $_POST["mail"] . "');";
			// echo $sql."<br>";
			$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
			while ($row = mysqli_fetch_array($result)) {
			}
			if (strcmp($row["password"], $_POST["password"]) === 1) {
				echo "El correo o la contraseña introducidos son incorrectos.";
			} else {
				$logeado_profesor = true;
			}
			mysqli_close($con);
		} else if ($_POST["pa"] == "a") {	//ALUMNO


			$result = mysqli_query($con, $sql) or die('Error en la consulta a la BDD');
			mysqli_close($con);
		}
		// $sql="INSERT INTO `alumno` (`idalumno`, `password`, `nombre_alumno`, `apellidos_alumno`, `mail_alumno`) VALUES (null,'".$_POST["password"]."','".$_POST["nombre"]."','".$_POST["apellido"]."','".$_POST["mail"]."')";
		?>
	</div>

	<form action="Login.html" method="POST" class="backbutton">
		<input type="submit" name="Volver" value="Volver">
	</form>

	<form action="Profesor_menu.html" method="POST">
	</form>

	<footer>
		&copy; <em id="date"></em> UPM
	</footer>

</body>

</html>