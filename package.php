<?php
/**
 * Script to generate package.xml file
 *
 * Taken from PEAR::Log, thanks Jon ;)
 *
 * $Id$
 */
require_once 'PEAR/PackageFileManager.php';
require_once 'Console/Getopt.php';

$version = '1.5.1';

$notes = <<<EOT
- td/th aren't longer case sensetive in addRow() and reletive functions
EOT;

$description = <<<EOT
The PEAR::HTML_Table package provides methods for easy and efficient design of HTML tables.
* Lots of customization options.
* Tables can be modified at any time.
* The logic is the same as standard HTML editors.
* Handles col and rowspans. 
* PHP code is shorter, easier to read and to maintain.
* Tables options can be reused.
EOT;

$package = new PEAR_PackageFileManager();

$result = $package->setOptions(array(
    'package'           => 'HTML_Table',
    'summary'           => 'PEAR::HTML_Table makes the design of HTML tables easy, flexible, reusable and efficient.',
    'description'       => $description,
    'version'           => $version,
    'state'             => 'stable',
    'license'           => 'PHP_License',
    'filelistgenerator' => 'cvs',
    'ignore'            => array('package.php', 'package.xml'),
    'notes'             => $notes,
    'changelogoldtonew' => false,
    'simpleoutput'      => true,
    'baseinstalldir'    => '/HTML',
    'packagedirectory'  => './',
    'dir_roles'         => array('docs'              => 'doc')
));

if (PEAR::isError($result)) {
    echo $result->getMessage();
}

$package->addMaintainer('manison',  'lead',        'Bertrand Mansion',      'bmansion@mamasam.com');
$package->addMaintainer('thesaur',  'lead',        'Klaus Guenther',   'thesaur@php.net');
$package->addMaintainer('dufuz',   'developer',   'Helgi Şormar',      'dufuz@php.net');

$package->addDependency('PEAR',      false,   'has', 'pkg', false);
$package->addDependency('HTML_Common',       '1.2.0',   'ge',  'pkg', false);

if (isset($_GET['make']) || (isset($_SERVER['argv'][1]) && $_SERVER['argv'][1] == 'make')) {
    $result = $package->writePackageFile();
} else {
    $result = $package->debugPackageFile();
}

if (PEAR::isError($result)) {
    echo $result->getMessage();
}
