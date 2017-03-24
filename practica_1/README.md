##Máquina MONGO
##Instalo mongo
- sudo iptables -I INPUT 5 -i eth0 -p tcp --dport 80 -m state --state NEW,ESTABLISHED -j ACCEPT
- iptables -nL | grep ACCEPT | grep NEW
- mongod --dbpath /var/lib/mongo/data/db --port 80 --fork --logpath /var/lib/mongo/mongod.log se especifica el puerto y que mongo se quede iniciado en segundo plano
- Para importar el dataset de prueba mongoimport --host=127.0.0.1 --port=80 --db test --collection restaurants --file primer-dataset.json

##Elimino los procesos de centos que estan en el puerto 80
sudo fuser -k 80/tcp

##cambio el fichero de configuración de mysql: /etc/my.conf
y pongo:

[mysqld]
port=80

##Arranco
sudo /sbin/service mysqld start comienza

##Máquina Apache
##Instalo php
sudo apt-get install libapache2-mod-php5 php5 php5-mcrypt
sudo apt-get install php5-mysql para instalar las librerias de php y mysql.
##Añado el script en php que se utilizará
sudo nano /var/www/html/script.php

########################################################################
##Máquina CENTOS: MYSQL
- hacemos yum update
- sudo yum install mysql-server para bajarlo
- sudo /sbin/service mysqld start para arrancar el servidor
- sudo /usr/bin/mysql_secure_installation para configurar mysql, introduciendo la contraseña del root que pone por defecto: ninguna, y metiendo la nueva. También m configuramos los accesos a la base de datos.
- Abro el puerto 3306 donde espera por defecto mysql con: sudo iptables -I INPUT 5 -i eth0 -p tcp --dport 3306 -m state --state NEW,ESTABLISHED -j ACCEPT
- reinicio el firewall de centos con sudo service iptables restart.
- Con el comando mysql -u root -p entramos en la consola de mysql para configurar las tablas que necesitaremos. Metemos contraseña.
-Introducimos CREATE DATABASE textos; en la shell de mysql, para crear


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
