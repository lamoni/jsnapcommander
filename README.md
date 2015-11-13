JSnap Commander
---------------

PHP classes for dealing with Juniper's Junos Snapshot Administrator (JSnap)

Example
-------
```php
$commander = new JSnapCommander(
    new JSnapTriggerDriverShell(
        new JSnapConfigTriggerShell(
            array(
                'JSnapExecutable'   => '/vagrant/junos-snapshot-administrator/bin/jsnap',
                'ConfigFile'   => '/vagrant/junos-snapshot-administrator/jsnap/samples/sample.conf',
                'JuiseExecutable'   => '/usr/jawa/bin/juise',
                'SwapPath'          => '/var/www/html/swap/',
                'DeviceUsername'    => 'root',
                'DevicePassword'    => 'Juniper'
            )
        )
    ),
    new JSnapIODriverFiles(
        new JSnapConfigIOFiles(
            array(
                'SwapPath' => '/var/www/html/swap/'
            )
        )
    )
);

$commander->snapShot('192.168.33.11');
```
