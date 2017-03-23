<?php

function consulta($query){
        $enlace = new mysqli("localhost","root","1234","textos");
        //Si la conexión a la base de datos ha fallado, que muestre un error:
        if($enlace->connect_errno) {
            echo "Fallo al contenctar a MySQL: (" . $enlace->connect_errno . ")";
        }else{
             //Seleccionamos la Base de Datos en la que vamos a trabajar
             $enlace->select_db("textos");
             //Realizamos la consulta deseada
             $resultado_consulta = $enlace->query($query);

             if(!$resultado_consulta){
               echo 'Se ha producido un error al ejecutar la consulta a la base de datos: '.$enlace->error;
             }

             if($query != "SELECT * FROM textos"){
               $resultado_consulta = $enlace->query("SELECT * FROM textos");

               if(!$resultado_consulta){
                 echo 'Se ha producido un error al listar los elementos de la base de datos: '.$enlace->error;
               }
             }

             //Mostramos el resultado de nuestra consulta sobre los elementos de la base de datos
             echo '<ul>';
             while($row = $resultado_consulta->fetch_assoc()){
                    echo '<li>'.$row["texto"].'<a href></li>';
             }
             echo '</ul>';
        }
    mysqli_close($enlace);
}
?>
