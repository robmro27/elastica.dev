#!/usr/bin/env bash

# Use single quotes instead of double quotes to make it work with special-character passwords
PASSWORD='admin'

# php 5.6
sudo add-apt-repository ppa:ondrej/php5-5.6

# update / upgrade
sudo apt-get update
sudo apt-get -y upgrade

# install apache 2.5 and php 5.6
sudo apt-get install -y apache2
sudo apt-get install python-software-properties php5 libapache2-mod-php php-mcrypt php-mysql

# install mysql and give password to installer
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password $PASSWORD"
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $PASSWORD"
sudo apt-get -y install mysql-server
sudo apt-get install php5-mysql

# install phpmyadmin and give password(s) to installer
# for simplicity I'm using the same password for mysql and phpmyadmin
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/dbconfig-install boolean true"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/app-password-confirm password $PASSWORD"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/admin-pass password $PASSWORD"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/app-pass password $PASSWORD"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2"
sudo apt-get -y install phpmyadmin

# enable mod_rewrite
sudo a2enmod rewrite

# setup hosts file
VHOST=$(cat <<EOF

<VirtualHost *:80>
    ServerName elastica.dev
    DocumentRoot "/var/www/html/elastica.dev/web"
    <Directory "/var/www/html/elastica.dev/web">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>

EOF
)
echo "${VHOST}" > /etc/apache2/sites-available/000-default.conf
echo "127.0.0.1 elastica.dev" >> /etc/hosts

# install git
sudo apt-get -y install git

# install Composer
curl -s https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# install symfony installer
sudo mkdir -p /usr/local/bin
sudo curl -LsS https://symfony.com/installer -o /usr/local/bin/symfony
sudo chmod a+x /usr/local/bin/symfony

# php unit
cd ~
wget https://phar.phpunit.de/phpunit-4.8.6.phar
chmod +x phpunit-4.8.6.phar
sudo mv phpunit-4.8.6.phar /usr/local/bin/phpunit

# java
sudo apt-get install openjdk-7-jre --assume-yes
#sudo add-apt-repository -y ppa:webupd8team/java
#sudo apt-get update
#sudo apt-get -y install oracle-java8-installer

# elastic search
cd ~
wget https://download.elastic.co/elasticsearch/elasticsearch/elasticsearch-1.7.2.deb
sudo dpkg -i elasticsearch-1.7.2.deb
sudo update-rc.d elasticsearch defaults

# For kibana from host
sudo echo "network.bind_host: 0" >> /etc/elasticsearch/elasticsearch.yml
sudo echo "network.host: 0.0.0.0" >> /etc/elasticsearch/elasticsearch.yml # http://127.0.0.1:5601/

sudo echo "index.number_of_shards: 1" >> /etc/elasticsearch/elasticsearch.yml
sudo echo "index.number_of_replicas: 0" >> /etc/elasticsearch/elasticsearch.yml
sudo service elasticsearch start

# head plugin
cd /usr/share/elasticsearch/
sudo bin/plugin -install mobz/elasticsearch-head # http://127.0.0.1:9200/_plugin/head/

# curl
sudo apt-get install php5-curl
#sudo apt-get install php-curl
#sudo echo "extension=php_curl.dll" >> /etc/php5/cli/php.ini
sudo service elasticsearch start

# restart apache
service apache2 restart

sudo chmod 0777 -R /var/www/html/elastica.dev/var








