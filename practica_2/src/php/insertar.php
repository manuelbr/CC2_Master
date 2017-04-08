<?php
    if (isset($_POST['tex'])) {
        $t = $_POST['tex'];
    }

    $enlace = mysqli_connect("172.17.0.138","root","1234","textos");
    //Si la conexión a la base de datos ha fallado, que muestre un error:

    if ($enlace->connect_errno) {
        echo "Fallo al contenctar a MySQL: (" . $enlace->connect_errno . ") ";
    }else{
        $table = "textos";
        //Se comprueba si ya existe la BD
        $res = $enlace->query("select * from ".$table." LIMIT 1");

        //No existe la base de datos
        if($res == FALSE){
                $crear = "CREATE TABLE textos (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, texto TEXT(30) NOT NULL)";

                if ($enlace->query($crear) != TRUE) {
                        echo "Error creating table: " . $enlace->error;
                }
        }

        //Seleccionamos la Base de Datos en la que vamos a trabajar
        $enlace->select_db($table);
        $resultado_consulta = $enlace->query("INSERT INTO textos(texto) VALUES('".$t."')");
        if(!$resultado_consulta){
          echo "Error insertando en la tabla: " . $enlace->error;
        }
        mysqli_close($enlace);
        header("Location: http://hadoop.ugr.es:15065", true, 301);
        exit();
    }
?>
