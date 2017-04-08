<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/theme.css">
    <title>Página de inicio</title>
  </head>

  <body>
      <h3>Bienvenido al servicio CRUD de Manuel Blanco Rienda</h3>
      <div class="contenido">
        <?php
          if(isset($_GET['inserta'])){
            echo '<h2>Inserta el elemento</h2>
                  <form action="'.'http://hadoop.ugr.es:15065/php/insertar.php'.'" method="'.'post'.'">
                      <input type="'.'text'.'" id="'.'tex'.'" name="'.'tex'.'" placeholder="'.'tex'.'">
                      <button type="'.'submit'.'">Insertar texto</button>
                  </form>';
          }else{
            echo '<form action="'.'http://hadoop.ugr.es:15065/?inserta=si'.'" method="'.'post'.'">
                    <button type="'.'submit'.'">Insertar texto</button>
                  </form>

                  <form action="'.'php/muestraResultado.php'.'" method="'.'get'.'">
                    <button type="'.'submit'.'">Listar textos</button>
                  </form>';
          }
        ?>
      </div>
  </body>
</html>
