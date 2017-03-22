<?php 
    
public function consulta(){     
        $enlace = new mysqli("docker.ugr.es:15064","root","382225946436a","3822$
        //Si la conexión a la base de datos ha fallado, que muestre un error:
        if ($enlace->connect_errno) {
            echo "Fallo al contenctar a MySQL: (" . $enlace->connect_errno . ")$
        }else{
                //Seleccionamos la Base de Datos en la que vamos a trabajar
                 $enlace->select_db("textos");
                 $resultado_consulta = $enlace->query("SELECT * FROM textos");
 
                echo '<ul>';
                 while($row = $resultado_consulta->fetch_assoc()){
                        echo '<li>'.$row["texto"].'</li>';
                 }
                 echo '</ul>'
        }
        mysqli_close($enlace);
?>
