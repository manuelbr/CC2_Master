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

- Nos bajamos la imagen de centos7 con mysql que deseamos. En mi caso descargaré la oficial de centos 7 limpia, para poder configurar mysql desde dentro. Esto lo hago con el comando "docker pull centos".
- Arrancamos el contenedor con la imagen que nos hemos bajado con el comando "docker run -i -d -p 15064:80 --name "mysqlManuelBlanco" centos", en el que redirigimos al puerto 80 las entradas por el puerto 15064 (el que tengo asignado como alumno para mi máquina secundaria) de centos y le damos el nombre a la máquina: "mysqlManuelBlanco"-
- Pasamos a entrar dentro de la máquina creada (previamente habiendo encontrado su identificador con docker ps) con el comando "docker exec -i -t e845f6317a13 /bin/bash".
- Instalamos wget con "yum install wget"
- hacemos "wget http://repo.mysql.com/mysql-community-release-el7-5.noarch.rpm" para obtener los repositorios de mysql (ya que centos no los proporciona de base).
- Con "rpm -ivh mysql-community-release-el7-5.noarch.rpm" abrimos el repositorio
- hacemos yum update
- sudo yum install mysql-server para bajarlo
- sudo /sbin/service mysqld start para arrancar el servidor
- sudo /usr/bin/mysql_secure_installation para configurar mysql, introduciendo la contraseña del root que pone por defecto: ninguna, y metiendo la nueva. También m configuramos los accesos a la base de datos.
- Abro el puerto 3306 donde espera por defecto mysql con: sudo iptables -I INPUT 5 -i eth0 -p tcp --dport 3306 -m state --state NEW,ESTABLISHED -j ACCEPT
- reinicio el firewall de centos con sudo service iptables restart.
- Con el comando mysql -u root -p entramos en la consola de mysql para configurar las tablas que necesitaremos. Metemos contraseña.
-Introducimos CREATE DATABASE textos; en la shell de mysql, para crear
