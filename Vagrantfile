# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = '2'

@script = <<SCRIPT
# Fix for https://bugs.launchpad.net/ubuntu/+source/livecd-rootfs/+bug/1561250
if ! grep -q "ubuntu-xenial" /etc/hosts; then
    echo "127.0.0.1 ubuntu-xenial" >> /etc/hosts
fi

# Install dependencies
add-apt-repository ppa:ondrej/php
apt-get update
apt-get install -y apache2 git curl php7.0 php7.0-bcmath php7.0-bz2 php7.0-cli php7.0-curl php7.0-intl php7.0-json php7.0-mbstring php7.0-opcache php7.0-soap php7.0-sqlite3 php7.0-xml php7.0-xsl php7.0-zip libapache2-mod-php7.0 php7.0-mysql 

apt-get install debconf-utils -y > /dev/null
debconf-set-selections <<< "mysql-server mysql-server/root_password password root"
debconf-set-selections <<< "mysql-server mysql-server/root_password_again password root"
apt-get install mysql-server -y > /dev/null

# Configure Apache
echo "<VirtualHost *:80>
	DocumentRoot /var/www/testh2/public
        ServerName www.testh2.dev
	AllowEncodedSlashes On

	<Directory /var/www/testh2/public>
		Options +Indexes +FollowSymLinks
		DirectoryIndex index.php index.html
		Order allow,deny
		Allow from all
		AllowOverride All
	</Directory>

	ErrorLog /var/log/apache2/error.log
	CustomLog /var/log/apache2/access.log combined
</VirtualHost>" > /etc/apache2/sites-available/000-default.conf
a2enmod rewrite
service apache2 restart

echo "Adding Hosts"
echo "127.0.0.1 www.testh2.dev" >> /etc/hosts

if [ -e /usr/local/bin/composer ]; then
    /usr/local/bin/composer self-update
else
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
fi

# Reset home directory of vagrant user
if ! grep -q "cd /var/www/testh2" /home/ubuntu/.profile; then
    echo "cd /var/www/testh2" >> /home/ubuntu/.profile
fi

cd /var/www/testh2
touch data/log/app.log
chmod go+rw -R /var/www/testh2/data/cache /var/www/testh2/data/log /var/www/testh2/data/uploads
cp docs/configs/local.php config/autoload/local.php

mysql> CREATE USER 'root'@'localhost' IDENTIFIED BY 'root';

# mysql -uroot -proot -e "CREATE DATABASE horisen_test CHARACTER SET utf8 COLLATE utf8_general_ci"
mysql -uroot -proot < docs/sqls/horisen_test.sql
mysql -uroot -proot < docs/sqls/data.sql

echo "** [ZF] Run the following command to install dependencies, if you have not already:"
echo "    vagrant ssh -c 'composer install'"
composer install
echo "** [ZF] Visit http://localhost:8080 in your browser for to view the application **"
SCRIPT

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = 'ubuntu/xenial64'
  config.vm.network "forwarded_port", guest: 80, host: 8080
  config.vm.synced_folder '.', '/var/www/testh2'
  config.vm.provision 'shell', inline: @script

  config.vm.provider "virtualbox" do |vb|
    vb.customize ["modifyvm", :id, "--memory", "512"]
    vb.customize ["modifyvm", :id, "--name", "testh2"]
  end
end
