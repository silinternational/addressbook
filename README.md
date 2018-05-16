**WARNING:** _THIS REPO IS NO LONGER MAINTAINED_

---

# Address Book - Extended Directory GUI #

## Purpose ##
This is intended to be a straight-forward web-based front-end for the Extended 
Directory API that supports both full browsers and mobile browsers.

## Environment / Project Setup ##
1. Install [VirtualBox](http://www.virtualbox.org/wiki/Downloads)
2. Install [Vagrant](http://downloads.vagrantup.com/)
3. Install [Chef-DK](http://getchef.com/downloads/chef-dk), [instructions](http://docs.opscode.com/install_dk.html)
4. Install Vagrant Berkshelf plugin ```vagrant plugin install vagrant-berkshelf --plugin-version 2.0.1```
5. Fork or Clone the repo, depending on your access: ```git@github.com:silinternational/addressbook.git```
6. Using shell/terminal, get to the project folder and run ```vagrant up```
7. Edit your hosts file to point "addressbook.local" to the VM's IP address, default in Vagrantfile is ```192.168.34.11```
8. Open browser and go to <https://addressbook.local/>
9. The app uses a self signed certificate so you'll need to accept the cert to access the app
