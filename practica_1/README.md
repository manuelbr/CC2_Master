#Máquina Apache
##Instalo php 
sudo apt-get install libapache2-mod-php5 php5 php5-mcrypt
sudo apt-get install php5-mysql para instalar las librerias de php y mysql.
##Añado el script en php que se utilizará
sudo nano /var/www/html/script.php

#Máquina CENTOS: MYSQL
- Nos bajamos wget con yum install wget
- Lo primero es bajarnos y añadir el repositorio a yum para que pueda contar con el: wget http://repo.mysql.com/mysql-community-release-el7-5.noarch.rpm.
- Lo instalamos con sudo rpm -ivh mysql-community-release-el7-5.noarch.rpm
- hacemos yum update
- sudo yum install mysql-server para bajarlo
- sudo /sbin/service mysqld start para arrancar el servidor
- sudo /usr/bin/mysql_secure_installation para configurar mysql, introduciendo la contraseña del root que pone por defecto: ninguna, y metiendo la nueva. También m configuramos los accesos a la base de datos.
- Abro el puerto 3306 donde espera por defecto mysql con: sudo iptables -I INPUT 5 -i eth0 -p tcp --dport 3306 -m state --state NEW,ESTABLISHED -j ACCEPT
- reinicio el firewall de centos con sudo service iptables restart.
- Con el comando mysql -u root -p entramos en la consola de mysql para configurar las tablas que necesitaremos. Metemos contraseña.
-Introducimos CREATE DATABASE textos; en la shell de mysql, para crear 

#Máquina ubuntu 2: MYSQL
- Hago sudo apt get update para actualizar los repositorios
- Instalo mysql-server con sudo apt-get install mysql-server-5.5 
- En ubuntu 14 me encontré el siguiente problema: el binding address encontraba
problemas al establecerse como 127.0.0.1, por lo que se cambió a 0.0.0.0 para permitir conexiones con cualquier interfaz de red, el cuál era el problema
- VOlvemos a ejecutar sudo apt-get install mysql-server-5.5 
- Ejecutamos sudo apt-get install mysql-server también le doy la configuración necesaria: contraseña de root y demás.
- Con el comando mysql -u root -p entramos en la consola de mysql para configurar las tablas que necesitaremos. Metemos contraseña.
-Introducimos CREATE DATABASE textos; en la shell de mysql, para crear 


