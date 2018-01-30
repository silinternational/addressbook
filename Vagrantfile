#@IgnoreInspection BashAddShebang
# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure(2) do |config|
  # The most common configuration options are documented and commented below.
  # For a complete reference, please see the online documentation at
  # https://docs.vagrantup.com.

  # Every Vagrant development environment requires a box. You can search for
  # boxes at https://atlas.hashicorp.com/search.
  config.vm.box = "ubuntu/trusty64"

  # Create a private network, which allows host-only access to the machine
  # using a specific IP.
  # config.vm.network "private_network", ip: "192.168.33.10"
  config.vm.network "private_network", ip: "192.168.34.11"


  # Set synced folder permissions
  config.vm.synced_folder "./", "/vagrant", owner: 33, group: 33

  # This provisioner runs on the first `vagrant up`.
  config.vm.provision "install", type: "shell", inline: <<-SHELL
    # Add Docker apt repository
    sudo apt-key adv --keyserver hkp://p80.pool.sks-keyservers.net:80 --recv-keys 58118E89F3A912897C070ADBF76221572C52609D
    sudo sh -c 'echo deb https://apt.dockerproject.org/repo ubuntu-trusty main > /etc/apt/sources.list.d/docker.list'
    sudo apt-get update -y
    # Uninstall old lxc-docker
    apt-get purge lxc-docker
    apt-cache policy docker-engine
    # Install docker and dependencies
    sudo apt-get install -y linux-image-extra-$(uname -r)
    sudo apt-get install -y docker-engine
    # Add user vagrant to docker group
    sudo groupadd docker
    sudo usermod -aG docker vagrant
    # Install Docker Compose
    curl -LsS https://github.com/docker/compose/releases/download/1.10.1/docker-compose-`uname -s`-`uname -m` > /usr/local/bin/docker-compose
    chmod +x /usr/local/bin/docker-compose

    # Create /home/vagrant/.bash_profile for env vars
    cat << EOF > /home/vagrant/.bash_profile
#!/bin/bash
COMPOSER_HOME=/home/vagrant/.composer; export COMPOSER_HOME
COMPOSER_CONFIG_FILE="${COMPOSER_HOME}/config.json"; export COMPOSER_CONFIG_FILE
COMPOSER_CACHE_DIR="${COMPOSER_HOME}/cache"; export COMPOSER_CACHE_DIR
# Get GID for DOCKER_UIDGID env var
GID=`id -g`
DOCKER_UIDGID="${UID}:${GID}"; export DOCKER_UIDGID
EOF

    chown vagrant:vagrant /home/vagrant/.bash_profile
    chmod +x /home/vagrant/.bash_profile

  SHELL

  # This provisioner runs on every `vagrant reload' (as well as the first
  # `vagrant up`), reinstalling from local directories
  config.vm.provision "recompose", type: "shell",
     run: "always", inline: <<-SHELL

    # Ensure env vars are loaded from bash_profile
    source /home/vagrant/.bash_profile

  SHELL

end
