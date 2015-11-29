# -*- mode: ruby -*-
# vi: set ft=ruby :

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box_url = "https://cloud-images.ubuntu.com/vagrant/trusty/current/trusty-server-cloudimg-amd64-vagrant-disk1.box"
  config.vm.box = "trusty64"

  config.vm.define "node0" do |node0|
      $script = <<SCRIPT

apt-get update 
apt-get install -y daemon rabbitmq-server unzip apache2 php5

# Rabbit ===============

rabbitmq-plugins enable rabbitmq_management
ervice rabbitmq-server restart

# Consul ===============

wget https://releases.hashicorp.com/consul/0.5.2/consul_0.5.2_linux_amd64.zip
unzip consul_0.5.2_linux_amd64.zip
chmod 755 consul
mv consul /usr/local/bin/
daemon -X "consul agent -server -bootstrap -data-dir /tmp/consul -node=consul0 -bind 172.20.20.10"

# Chinchilla ===========


#wget https://drone.io/github.com/benschw/chinchilla/files/chinchilla.gz
#gunzip chinchilla.gz
#chmod 755 chinchilla
cp /vagrant/chinchilla /usr/local/bin/

daemon -X "chinchilla -config /vagrant/chinchilla.yaml"

# Apache ===============

a2enmod php5
a2enmod rewrite

cp /vagrant/apache-vhost.conf /etc/apache2/sites-enabled/000-default.conf

service apache2 restart
rm -rf /vagrant/html

SCRIPT

      node0.vm.provision "shell", inline: $script
      node0.vm.hostname = "node0"
      node0.vm.network "private_network", ip: "172.20.20.10"
      node0.vm.synced_folder "./", "/var/www/"
end



  config.vm.provider :virtualbox do |vb|
    vb.customize ["modifyvm", :id, "--memory", "1024"]
  end

end
