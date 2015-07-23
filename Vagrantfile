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
  # config.vm.box = "ubuntu/trust64"
  config.vm.box = "AlbanMontaigu/boot2docker"
  config.vm.box_version = "= 1.7.0"

  # The boot2docker box is not configured as a vagrant 'base box', so it is
  # necessary to specify how to ssh in.
  config.ssh.username = "docker"
  config.ssh.password = "tcuser"
  config.ssh.insert_key = true

  # Create a forwarded port mapping which allows access to a specific port
  # within the machine from a port on the host machine. In the example below,
  # accessing "localhost:8080" will access port 80 on the guest machine.
  # config.vm.network "forwarded_port", guest: 80, host: 8080

  # Create a private network, which allows host-only access to the machine
  # using a specific IP.
  # config.vm.network "private_network", ip: "192.168.33.10"
  config.vm.network "private_network", ip: "192.168.34.11"

  # Create a public network, which generally matched to bridged network.
  # Bridged networks make the machine appear as another physical device on
  # your network.
  # config.vm.network "public_network"

  # Share an additional folder to the guest VM. The first argument is
  # the path on the host to the actual folder. The second argument is
  # the path on the guest to mount the folder. And the optional third
  # argument is a set of non-required options.

  # These are the folders for container data
  config.vm.synced_folder "./application", "/data", mount_options: ["uid=33","gid=33"]

  # Only sync it if we have the ENV variables is set
  if ENV['DOCKER_IMAGEDIR_PATH']
    config.vm.synced_folder ENV['DOCKER_IMAGEDIR_PATH'], "/preload-images"
  else
    # The escape sequences are for colored output
    puts "\033[31mSet \033[32mDOCKER_IMAGEDIR_PATH\033[31m in your environment to import local tar archives containing"
    puts "images into docker's image-store.\033[0m"
  end

  
  # Fix dns resolution speed issues: http://serverfault.com/questions/453185/vagrant-virtualbox-dns-10-0-2-3-not-working?rq=1
  config.vm.provider "virtualbox" do |vb|
    vb.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
  end

  # Enable provisioning with a shell script. Additional provisioners such as
  # Puppet, Chef, Ansible, Salt, and Docker are also available. Please see the
  # documentation for more information about their specific syntax and use.
  config.vm.provision "bootstrap", type: "shell", inline: <<-SHELL
     # Copy the home directory to persistent storage
     mkdir /mnt/sda2/home
     cp -r /home/docker /mnt/sda2/home
     chown -R docker.staff /mnt/sda2/home/docker

     #Switcheroo 
     mount --bind /mnt/sda2/home/docker /home/docker
     cd /home/docker

     # Download Python and Pip
     mkdir /mnt/sda2/tce-persist
     chown docker.staff /mnt/sda2/tce-persist
     chmod 775 /mnt/sda2/tce-persist

     mount --bind /mnt/sda2/tce-persist /mnt/sda2/tmp/tce/optional
     sudo -u tc tce-load -w python
     umount /mnt/sda2/tmp/tce/optional

     mkdir /mnt/sda2/pip
     cd /mnt/sda2/pip

     curl https://bootstrap.pypa.io/get-pip.py > get-pip.py
     chmod u+x get-pip.py

     # Turn inter-container communication off
     echo 'EXTRA_ARGS="-icc=false"' >> /var/lib/boot2docker/profile

     /etc/init.d/docker stop
     iptables -F
     /etc/init.d/docker start

     # Convenience
     if mount | grep /compose 1>/dev/null; then
       ln -s /compose /home/docker/compose
     fi
   SHELL


  # Configured to run on every `vagrant reload'
  config.vm.provision "recompose", type: "shell", run: "always", inline: <<-SHELL
     #Switcheroo 
     mount --bind /mnt/sda2/home/docker /home/docker
     cd /home/docker

     # Install python and Docker-Compose
     mount --bind /mnt/sda2/tce-persist /mnt/sda2/tmp/tce/optional
     sudo -u tc tce-load -ic python
     umount /mnt/sda2/tmp/tce/optional

     cd /mnt/sda2/pip
     echo "Running get-pip.py"
     ./get-pip.py

     pip install -b build -t install -U docker-compose

     # Mixing of tabs and spaces is intentional
     cat <<- EOF > /usr/local/bin/docker-compose
	#!/usr/local/bin/python

	# -*- coding: utf-8 -*-
	import re
	import sys

        # Need to point the PYTHONPATH at the right place
	sys.path.append("/mnt/sda2/pip/install")

	from compose.cli.main import main

	if __name__ == '__main__':
	    sys.argv[0] = re.sub(r'(-script\.pyw|\.exe)?', '', sys.argv[0])
	    sys.exit(main())
	EOF
     chmod 755 /usr/local/bin/docker-compose

     # Preload docker with images (but only if there is a synced folder)
     if mount | grep /preload-images 1>/dev/null; then
       echo "Preloading the docker-daemon with images"
       cd /preload-images

       allready_images=$(docker images -q)

       for file in $(ls | egrep '.tar$' ); do
         name=$( basename $file .tar )

         # Scan through the images we have and ensure that they are not already loaded
         for i in $allready_images; do 
           if [[ $i == $name ]]; then
             # Skip this file
             echo "--> Skipping $name.tar (already loaded)"
             continue 2
           fi
         done

         echo "--> Loading $name.tar"
         docker load < $file
       done
     fi

     # Run Compose (which pulls images it doesn't have imported, as well as updates to the others)
     cd /vagrant
     docker-compose up -d

     # Update the image directory with any changes
     if mount | grep /preload-images 1>/dev/null; then
       echo "Adding images in /preload-images."
       cd /preload-images

       for i in $(docker images -q); do
         if ! [[ -f $i.tar ]]; then
           echo "--> Saving $i.tar"
           docker save -o $i.tar $i
         else
           # Update the timestamps on the ones we use
           echo "--> skipping $i.tar (already exists)"
           touch $i.tar
         fi
       done
     fi
  SHELL
end