<!DOCTYPE html>
<html lang="en">
  <?php require "php/consultar.php"; ?>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Página de muestra de resultados</title>
  </head>

  <body>
        <h3>Todos los textos que hay en la base de datos son: </h3>
        <?php
            $action = "list";
            if(isset($_GET['action'])){
              $action = $_GET['action'];
            }

            switch($action){
              case "list": consulta();
                            break;
              case "update":
            }

         ?>
  </body>
</html>
