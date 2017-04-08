###################################################################################
## Máquina Apache
- Hago docker pull eboraas/apache-php para descargarme la última imagen disponible con ubuntu, apache y php, creado por el usuario eboras de dockerhub.
- Hago docker run -i -d -p 15065:80 --name "apacheManuelBlanco" eboraas/apache-php para ejecutar el contenedor, dándole un nombre y redireccionando con la opción -p el puerto 80 al 15065 que es por el que se accederá de forma remota.
- Ahora tengo que conseguir el identificador corto del contenedor (aunque en el comando anterior ya me han dado el identificador largo, ese no me sirve para entrar en él).
- Con docker exec -i -t 569090ad4b77 /bin/bash accedo al contenedor.
- Compruebo la disponibilidad del servicio de apache con el comando: "service apache2 status". Una nota a destacar es que en este contenedor ya entramos en modo root desde el inicio por lo que no hay que acompañar las órdenes que usamos con "sudo".
- Instalo git con el comando "apt-get install git" para poder hacer el despliegue de la app web desde mi repositorio de git.



########################################################################
##Máquina ubuntu 2: MYSQL
- Hago sudo apt get update para actualizar los repositorios
- Instalo mysql-server con sudo apt-get install mysql-server-5.5
- En ubuntu 14 me encontré el siguiente problema: el binding address encontraba
problemas al establecerse como 127.0.0.1, por lo que se cambió a 0.0.0.0 para permitir conexiones con cualquier interfaz de red, el cuál era el problema
- VOlvemos a ejecutar sudo apt-get install mysql-server-5.5
- Ejecutamos sudo apt-get install mysql-server también le doy la configuración necesaria: contraseña de root y demás.
- Con el comando mysql -u root -p entramos en la consola de mysql para configurar las tablas que necesitaremos. Metemos contraseña.
-Introducimos CREATE DATABASE textos; en la shell de mysql, para crear
- Introducimos GRANT ALL ON *.* to root@'172.17.0.91' IDENTIFIED BY '1234'
- Introducimos FLUSH PRIVILEGES.
