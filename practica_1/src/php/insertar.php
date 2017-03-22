?php       
    if (isset($_POST['tex'])) {
        $t = $_POST['tex']; 
    }

    $m = mysqli_connect("192.168.10.64","root","1234","textos");
  
    //Si la conexión a la base de datos ha fallado, que muestre un error:
   
    if ($enlace->connect_errno) {
        echo "Fallo al contenctar a MySQL: (" . $enlace->connect_errno . ") " .$
    }else{
        $table = "textos"
        //Se comprueba si ya existe la BD
        $res = $enlace->query("SHOW TABLES LIKE $table");

        //No existe la base de datos
        if(mysql_num_rows($res) <= 0){
                $crear = "CREATE TABLE textos (
                    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        texto TEXT(30) NOT NULL

                )";

                if ($enlace->query($crear) != TRUE) {
                        echo "Error creating table: " . $enlace->error;
                }
        }       

        //Seleccionamos la Base de Datos en la que vamos a trabajar
        $enlace->select_db($table);
        $resultado_consulta = $enlace->query("INSERT INTO $table VALUES(DEFAULT$
        mysqli_close($enlace);
        header("Location: http://docker.ugr.es:15065", true, 301);
        exit(); 
    }
?>

