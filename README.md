# Address Book - Extended Directory GUI #
[ ![Codeship Status for silinternational/addressbook](https://codeship.io/projects/1c70f900-0529-0132-a8fd-2613281a1d2c/status)](https://codeship.io/projects/30816)

## Purpose ##
This is intended to be a straight-forward web-based front-end for the Extended 
Directory API that supports both full browsers and mobile browsers.

## Environment / Project Setup ##
1. Install [VirtualBox](http://www.virtualbox.org/wiki/Downloads)
2. Install [Vagrant](http://downloads.vagrantup.com/)
3. Install [Chef-DK](http://getchef.com/downloads/chef-dk), [instructions](http://docs.opscode.com/install_dk.html)
4. Install Vagrant Berkshelf plugin ```vagrant plugin install vagrant-berkshelf --plugin-version 2.0.1```
5. Fork or Clone the repo, depending on your access:
   ```git@github.com:silinternational/addressbook.git```
6. Using shell/terminal, get to the project folder and run ```vagrant up```
8. Edit your hosts file to point "addressbook.local" to the VM's IP address, default in Vagrantfile is ```192.168.34.11```
9. Open browser and go to <https://addressbook.local/>
10. The app uses a self signed certificate so you'll need to accept the cert to access the app
