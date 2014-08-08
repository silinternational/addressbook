source "https://api.berkshelf.com"

def opsworks_cookbook(name, version = '>= 0.0.0', options = {})
  cookbook name, version, { path: "~/code/opsworks-cookbooks/#{name}" }.merge(options)
end

cookbook "yum", github: "opscode-cookbooks/yum"
cookbook "yum-epel", github: "opscode-cookbooks/yum-epel"
cookbook "perl", github: "opscode-cookbooks/perl"
cookbook "php", github: "opscode-cookbooks/php"
cookbook "apache2", github: "onehealth-cookbooks/apache2"
cookbook "addressbook", github: "silinternational/chef-cookbooks", rel: "addressbook"
cookbook "silphp", github: "silinternational/chef-cookbooks", rel: "silphp"
cookbook "silapache2", github: "silinternational/chef-cookbooks", rel: "silapache2"
cookbook "simplesamlphp", github: "silinternational/chef-cookbooks", rel: "simplesamlphp"
cookbook "hosts_file", github: "hw-cookbooks/hosts_file"
