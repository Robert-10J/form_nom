<?php

    require 'config/db.php';
    $db = conectarDB();

    $nombre = "";
    $email = "";
    $departamento = "";
    $antiguedad = 0;
    $estadoCivil = "";
    $sueldoDiario = 0;
    $diasTrabajados = 0;
    $sueldoBono = 0;
    $resultadoSueldo = 0;

    $alertas = [];

    //EMAIL
    $emailDest = "";
    $subject = "";
    $message = "";
    $remitente = "Alejandra Celestino Bautista <bautistacelestinoalejandra@gmail.com>";

    if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
        $nombre = mysqli_real_escape_string( $db, $_POST["nombre"]);
        $email = mysqli_real_escape_string( $db, $_POST["email"]);
        $departamento = mysqli_real_escape_string( $db, $_POST["departamento"]);
        $antiguedad = mysqli_real_escape_string( $db, $_POST["antiguedad"]);
        $estadoCivil = mysqli_real_escape_string( $db, $_POST["estadoCivil"]);
        $sueldoDiario = mysqli_real_escape_string( $db, $_POST["sueldoDiario"]);
        $diasTrabajados = mysqli_real_escape_string( $db, $_POST["diasTrabajados"]);

        if( !$nombre ) {
            $alertas[] = 'El nombre es obligatorio';
        }
        if( !$email ) {
            $alertas[] = 'El email es obligatorio';
        }
        if( !$departamento ) {
            $alertas[] = 'El departamento es obligatorio';
        }
        if( !$antiguedad ) {
            $alertas[] = 'El antiguedad es obligatorio';
        }
        if( !$estadoCivil ) {
            $alertas[] = 'El estadoCivil es obligatorio';
        }
        if( !$sueldoDiario ) {
            $alertas[] = 'El sueldoDiario es obligatorio';
        }
        if( !$diasTrabajados ) {
            $alertas[] = 'Los días trabajados son obligatorios';
        }
        
        $totalSueldo = $sueldoDiario * $diasTrabajados;
    
        if( $antiguedad >= 10 ) {
            $sueldoBono = $totalSueldo * .30;
            $bono = $sueldoBono + $totalSueldo;
        }
    
        $resultadoSueldo = $antiguedad >= 10 ? $bono : $totalSueldo;

        if( empty($alertas) ) {
            $query = "INSERT INTO nominas (nombre, correoElectronico,departamento, antiguedad, estadoCivil, sueldoDiario, diasTrabajados, bono, sueldoQuincenal)";
            $query .= "VALUES('$nombre', '$email','$departamento', '$antiguedad', '$estadoCivil', '$sueldoDiario', '$diasTrabajados', '$sueldoBono','$resultadoSueldo')";
            $resultado = mysqli_query( $db, $query );

            if( $resultado ) {
                header('Location: /');
            }
        }
    }


    $emailDest = $email;
    $subject = "Nomina del empleado" . $nombre;
    $message = "<html>";
    $message .= "Holaa<strong>" . $nombre . "</strong>" . "tu sueldo quincenal es de: $" . $resultadoSueldo;
    $message .= "</html>";

    //mail($email, $subject, $message, $remitente);
    
    $querySelect = "SELECT * FROM nominas";
    $resultadoConsulta = mysqli_query( $db, $querySelect );

              /*   echo "<pre>";
                var_dump( $resultadoConsulta );
                echo "</pre>";
                exit; */
?>

<!DOCTYPE html>
<html lang="es">
    <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nomina</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Nomina</h1>
    <section>
        <div class="contenedor">
            <form action="/" method="POST" class="formulario">
                <div class="campo">
                    <label class="campo__label" for="nombre">Nombre:</label>
                    <input 
                        class="campo__field" 
                        type="text" 
                        id="nombre" 
                        name="nombre" 
                        placeholder="Ingrese su nombre" 
                    />
                </div>
                <div class="campo">
                    <label class="campo__label" for="email">Correo Electronico:</label>
                    <input 
                        class="campo__field" 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="Ingrese su correo electronico" 
                    />
                </div>
                <div class="campo">
                    <label class="campo__label" for="departamento">Departamento:</label>
                    <select name="departamento" id="departamento" class="campo__field">
                        <option value="">-- Seleccione --</option>
                        <option value="isc">ISC</option>
                        <option value="ibq">IBQ</option>
                        <option value="ige">IGE</option>
                    </select>
                </div>
                <div class="campo">
                    <label class="campo__label" for="antiguedad">Antiguedad:</label>
                    <input 
                        class="campo__field" 
                        type="number" 
                        id="antiguedad" 
                        name="antiguedad" 
                        placeholder="Ingrese su antiguedad" 
                    />
                </div>
                <div class="campo">
                    <label class="campo__label" for="estadoCivil">Estado Civil:</label>
                    <select name="estadoCivil" id="estadoCivil" class="campo__field">
                        <option value="">-- Seleccione --</option>
                        <option value="soltero">Soltero</option>
                        <option value="casado">Casado</option>
                    </select>
                </div>
                <div class="campo">
                    <label class="campo__label" for="sueldoDiario">Sueldo Diario:</label>
                    <input 
                        class="campo__field" 
                        type="number" 
                        id="sueldoDiario" 
                        name="sueldoDiario" 
                        placeholder="Ingrese su sueldo diario" 
                    />
                </div>
                <div class="campo">
                    <label class="campo__label" for="diasTrabajados">Días Trabajados:</label>
                    <input 
                        class="campo__field" 
                        type="number" 
                        id="diasTrabajados" 
                        name="diasTrabajados" 
                        placeholder="Ingrese el total de días trabajados" 
                    />
                </div>
                <div class="campo">
                    <label class="campo__label">¿Quiere recibir notificaciones?:</label>
                    <label for="notificacionSi">Si</label>
                    <input
                        type="radio"
                        name="notificacionSi"
                        value="si"
                        class="campo__field"
                        id="notificacionSi"
                    />
                    <label for="notificacionNo">No</label>
                    <input
                        type="radio"
                        name="notificacionNo"
                        id="notificacionNo"
                        value="no"
                        class="campo__field"
                    />
                </div>

                <input type="submit" value="Calcular" class="boton" />
            </form>
        </div>

        <div class="contenedor">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Empleado</th>
                        <th>Dpt.</th>
                        <th>Antiguedad.</th>
                        <th>E.Civil.</th>
                        <th>Sueldo Quincenal</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while( $nominaU = mysqli_fetch_assoc($resultadoConsulta) ): ?>
                    <tr>
                        <td><?php echo $nominaU['id']; ?></td>
                        <td><?php echo $nominaU['nombre']; ?></td>
                        <td><?php echo $nominaU['departamento']; ?></td>
                        <td><?php echo $nominaU['antiguedad'] . " años"; ?></td>
                        <td><?php echo $nominaU['estadoCivil']; ?></td>
                        <td><?php echo $nominaU['sueldoQuincenal']; ?></td>
                        <td>
                            <img src="editar.png" alt="">
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>
</body></html>