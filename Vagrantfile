# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure('2') do |config|
    config.vm.box       = 'ubuntu/xenial64'
    config.vm.host_name = 'talkly.foo'
    config.ssh.forward_agent = true

    config.vm.network 'private_network', ip: '192.168.142.42'
    config.vm.network 'forwarded_port', guest: 3000, host: 3000
    config.vm.synced_folder '.', '/srv/share', id: 'vagrant-share', :nfs => true, :chown_ignore => true, :chmod_ignore => true, :mount_options => ['rw', 'vers=3', 'tcp', 'fsc', 'actimeo=2']
    config.vm.synced_folder '.', '/vagrant', disabled: true

    config.vm.provider :virtualbox do |virtualbox|
        virtualbox.customize ['modifyvm', :id, '--memory', 2048]
    end

    config.vm.provision 'ansible_local' do |ansible|
        ansible.provisioning_path = '/srv/share/ansible'
        ansible.playbook          = 'site.yml'
        ansible.inventory_path    = 'inventory/devbox'
        ansible.limit             = 'devbox'
    end
end
