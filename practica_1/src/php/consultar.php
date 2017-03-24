<?php

function consulta($query){
  $resultado = "mal";
  $enlace = new mysqli("192.168.10.64","root","1234","textos");
  //Si la conexión a la base de datos ha fallado, que muestre un error:
  if($enlace->connect_errno) {
      echo "Fallo al conectar a MySQL: (" . $enlace->connect_errno . ")";
  }else{
    //Seleccionamos la Base de Datos en la que vamos a trabajar
    $enlace->select_db("textos");
    //Realizamos la consulta deseada
    $resultado_consulta = $enlace->query($query);

    if(!$resultado_consulta){
      echo 'Se ha producido un error al ejecutar la consulta a la base de datos: '.$enlace->error;
    }else{
      $resultado = "bien";
    }
    mysqli_close($enlace);
  }

  return $resultado;
}
?>
