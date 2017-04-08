###################################################################################
## Máquina Apache
- Hago docker pull eboraas/apache-php para descargarme la última imagen disponible con ubuntu, apache y php, creado por el usuario eboras de dockerhub.
- Hago docker run -i -d -p 15065:80 --name "apacheManuelBlanco" eboraas/apache-php para ejecutar el contenedor, dándole un nombre y redireccionando con la opción -p el puerto 80 al 15065 que es por el que se accederá de forma remota.
- Ahora tengo que conseguir el identificador corto del contenedor (aunque en el comando anterior ya me han dado el identificador largo, ese no me sirve para entrar en él).
- Con docker exec -i -t 569090ad4b77 /bin/bash accedo al contenedor.
- Compruebo la disponibilidad del servicio de apache con el comando: "service apache2 status". Una nota a destacar es que en este contenedor ya entramos en modo root desde el inicio por lo que no hay que acompañar las órdenes que usamos con "sudo".
- Instalo git con el comando "apt-get install git" para poder hacer el despliegue de la app web desde mi repositorio de git.




########################################################################
## Máquina CENTOS: MYSQL
- Nos bajamos la imagen de centos7 con mysql que deseamos. En mi caso descargaré la oficial de centos 7 limpia, para poder configurar mysql desde dentro. Esto lo hago con el comando "docker pull jdeathe/centos-ssh-mysql".
- Arrancamos el contenedor con la imagen que nos hemos bajado con el comando "docker run -i -d -p 15064:3306 --name "mysqlManuelBlanco" --env "MYSQL_ROOT_PASSWORD=contraseñar" jdeathe/centos-ssh-mysql", en el que redirigimos al puerto 3306 las entradas por el puerto 15064 (el que tengo asignado como alumno para mi máquina secundaria) de centos, le damos el nombre a la máquina: "mysqlManuelBlanco" y le especificamos la contraseña que tendrá el usuario root.
- Pasamos a entrar dentro de la máquina creada con el comando "docker exec -it mysqlManuelBlanco /bin/bash".
- Comprobamos que el servicio mysql esté corriendo con "service mysqld status".
- Con el comando mysql -u root -p entramos en la consola de mysql para configurar las tablas que necesitaremos. Metemos contraseña.
-Introducimos CREATE DATABASE textos; en la shell de mysql, para crear
