Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/trusty64"
  config.vm.provider :virtualbox do |v|
    v.customize ["modifyvm", :id, "--memory", 2048]
  end
  config.vm.provision :shell, path: "bootstrap.sh"
  config.vm.network :forwarded_port, guest: 80, host: 8080
  config.vm.network :forwarded_port, guest: 3306, host: 33060
  config.vm.network :forwarded_port, guest: 9200, host: 9200
  config.vm.network "private_network", ip: "192.168.10.10"
  config.vm.synced_folder "C:/xampp/htdocs/elastica.dev/html/", "/var/www/html/" , :nfs => true
end