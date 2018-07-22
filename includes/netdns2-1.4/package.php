<?php

date_default_timezone_set('America/Toronto');

ini_set("include_path", ".:/usr/local/php/lib/php/:/usr/share/pear/");
require_once 'PEAR/PackageFileManager/File.php';
require_once 'PEAR/PackageFileManager2.php';

$pkg = new PEAR_PackageFileManager2;

$e = $pkg->setOptions(array(
        
        'baseinstalldir'    => '/',
        'packagedirectory'  => '/u/devel/www/net_dns/Net_DNS2/',
        'ignore'            => array(
            'package.php',
            'package.xml',
            'TODO',
            'composer.json'
        ),
        'installexceptions' => array('phpdoc' => '/*'),
        'dir_roles'         => array(
            'tests'     => 'test'
        ),
        'exceptions'        => array(
            'LICENSE'   => 'doc',
            'README.md' => 'doc'
        )
));

$pkg->setPackage('Net_DNS2');
$pkg->setSummary('PHP5 Resolver library used to communicate with a DNS server.');
$pkg->setDescription("Provides (roughly) the same functionality as Net_DNS, but using PHP5 objects, exceptions for error handling, better sockets support.\n\nThis release is (in most cases) 2x - 10x faster than Net_DNS, as well as includes more RR's (including DNSSEC RR's), and improved sockets and streams support.");
$pkg->setChannel('pear.php.net');
$pkg->setAPIVersion('1.4.4');
$pkg->setReleaseVersion('1.4.4');
$pkg->setReleaseStability('stable');
$pkg->setAPIStability('stable');
$pkg->setNotes(
"- bugfix when returning an empty bitmap-type in BitMap.php - patch from BugMaster510945.\n" .
"- added the BIND 9 private record RR (TYPE65534) - patch from BugMaster510945.\n" .
"- added DNSSEC algorithms 13-16 (ECDSAP256SHA256, ECDSAP384SHA384, ED25519, and ED448).\n" .
"- added SSHFP algoritm ED25519.\n" .
"- modified Net_DNS2::sendPacket() to use current()/next() rather than the deprecated each() (deprecated in 7.2).\n"
);
$pkg->setPackageType('php');
$pkg->addRelease();
$pkg->setPhpDep('5.2.1');
$pkg->setPearinstallerDep('1.4.0a12');
$pkg->addMaintainer('lead', 'mikepultz', 'Mike Pultz', 'mike@mikepultz.com');
$pkg->setLicense('BSD License', 'http://www.opensource.org/licenses/bsd-license.php');
$pkg->generateContents();

$pkg->writePackageFile();

?>
