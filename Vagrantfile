# -*- mode: ruby -*-
# vi: set ft=ruby :

class ::Hash
    def deep_merge(second)
        merger = proc { |key, v1, v2| Hash === v1 && Hash === v2 ? v1.merge(v2, &merger) : v2 }
        self.merge(second, &merger)
    end
end

Vagrant.configure("2") do |config|
    # All Vagrant configuration is done here. The most common configuration
    # options are documented and commented below. For a complete reference,
    # please see the online documentation at vagrantup.com.

    config.vm.box = "ubuntu/trusty64"

    # Make sure Chef is installed
    config.omnibus.chef_version = :latest

    config.vm.network :private_network, ip: "192.168.34.11"
    config.vm.synced_folder "application/", "/var/lib/addressbook", owner: "www-data", group: "www-data"

    config.vm.provider "virtualbox" do |vb|
      # Fix dns resolution speed issues: http://serverfault.com/questions/453185/vagrant-virtualbox-dns-10-0-2-3-not-working?rq=1
      vb.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
    end

    # Enable Berkshelf plugin
    config.berkshelf.enabled = true

    config.vm.provision :chef_solo do | chef |
        chef.add_recipe "perl"
        chef.add_recipe "opsworks_initial_setup"
        chef.add_recipe "mod_php5_apache2"
        chef.add_recipe "apache2"
        chef.add_recipe "apache2::mod_php5"
        chef.add_recipe "apache2::deploy_vhosts"
        chef.add_recipe "hosts_file"
        chef.add_recipe "hosts_file::custom_entries"
        chef.add_recipe "php::ini"
        chef.add_recipe "addressbook"
        chef.add_recipe "php::configure"
        chef.add_recipe "php::composer"
        chef.add_recipe "simplesamlphp::configure"

        # Load JSON data from files main.json and then overwrite/merge values from local.json
        jsonData = JSON.parse( IO.read('main.json'))
        if File.exists?('local.json')
          localData = JSON.parse( IO.read('local.json'))
          jsonData.deep_merge!(localData)
        end

        chef.json = jsonData
    end

end