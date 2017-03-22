<!DOCTYPE html>
<html lang="en">
  <?php require "php/consulta.php"; ?>  
  
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head co$
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Página de muestra de resultados</title>

  </head>


  <body>
        <h3>Todos los textos que hay en la base de datos son: </h3>
        <?php consulta(); ?>
  </body>
</html> 

