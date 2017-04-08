<!DOCTYPE html>
<html lang="en">
  <?php require "consultar.php"; ?>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../css/theme.css">
    <title>Página de muestra de resultados</title>
  </head>

  <body>
        <h3>Bienvenido al servicio CRUD de Manuel Blanco Rienda</h3>
        <div class="contenido">
        <?php
            if(isset($_GET['eliminar'])){
              $resultado = consulta("DELETE FROM textos WHERE id='".$_GET['eliminar']."'");
              if($resultado == "bien"){
                echo '<h2>¡El elemento se ha eliminado!<h2>
                      <form action="'.'http://hadoop.ugr.es:15065'.'" method="'.'get'.'">
                          <button type="'.'submit'.'">Volver al inicio</button>
                      </form>';
              }else{
                echo '<h2>Se ha producido un error...:(<h2>';
              }
            }else
                if(isset($_GET['actualizar']) && isset($_POST['tex'])){
                  $resultado = consulta("UPDATE textos SET texto='".$_POST['tex']."' WHERE id='".$_GET['actualizar']."'");

                  if($resultado == "bien"){
                    echo '<h2>¡El elemento se ha actualizado!<h2>
                          <form action="'.'http://hadoop.ugr.es:15065'.'" method="'.'get'.'">
                              <button type="'.'submit'.'">Volver al inicio</button>
                          </form>';
                  }else{
                    echo '<h3>Se ha producido un error...:(<h3>';
                  }
                }else
                  if(isset($_GET['actualizar'])){
                    echo '<h2>Actualiza el elemento<h2>
                          <form action="'.'http://hadoop.ugr.es:15065/php/muestraResultado.php?actualizar='.$_GET['actualizar'].'" method="'.'post'.'">
                              <label for="formGroupExampleInput">Inserta texto</label>
                              <input type="'.'text'.'" id="'.'tex'.'" name="'.'tex'.'" placeholder="'.'tex'.'">
                              <button type="'.'submit'.'">Insertar texto</button>
                          </form>';
                  }else{
                    echo '<h2>Listado de textos: </h2>';
                    $enlace = new mysqli("172.17.0.138","root","1234","textos");
                    //Si la conexión a la base de datos ha fallado, que muestre un error:
                    if($enlace->connect_errno) {
                      echo "Fallo al contenctar a MySQL: (" . $enlace->connect_errno . ")";
                    }else{
                      $enlace->select_db("textos");
                      $resultado_consulta = $enlace->query("SELECT * FROM textos");

                      if(!$resultado_consulta){
                        echo 'Se ha producido un error al listar los elementos de la base de datos: '.$enlace->error;
                      }else{
                        //Mostramos el resultado de nuestra consulta sobre los elementos de la base de datos
                        echo '<ul>';
                        while($row = $resultado_consulta->fetch_assoc()){
                          echo '<li>'.$row["texto"].' <a href="'.'http://hadoop.ugr.es:15065/php/muestraResultado.php?actualizar='.$row["id"].'">Actualizar</a> <a href="'.'http://hadoop.ugr.es:15065/php/muestraResultado.php?eliminar='.$row["id"].'">Eliminar</a></li>';
                        }
                        echo '</ul>';
                        echo '<form action="'.'http://hadoop.ugr.es:15065'.'" method="'.'get'.'">
                                  <button type="'.'submit'.'">Volver al inicio</button>
                              </form>';
                      }
                    }
                }
        ?>
      </div>
  </body>
</html>
