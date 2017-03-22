<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head co$
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Página de inicio</title>
  </head>

  <body>
      <form action="php/inserta.php" method="post">
          <label for="formGroupExampleInput">Inserta Texto</label>
          <input type="text" id="tex" name="tex" placeholder="tex">
          <button type="submit">Insertar Texto</button>
      </form>

      <form action="muestraResultado.html" method="get">
          <button type="submit">Recupera último texto insertado</button>
      </form>

  </div>
</body>
</html>

