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
- td/th aren't longer case sensetive in addRow() and relative functions
- Added the possibility to specify on which row the cols should be counted. (Bertrand)
- #786, if the value was a zero, the cell content was converted to the autofill value. (Bertrand)
- #1734, _adjustEnd added a extra empty column if there was only one column being processed
- setHeaderContents can now accept attributes, but it's optional. request #2030
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

$package->addMaintainer('mansion',  'lead',        'Bertrand Mansion',      'bmansion@mamasam.com');
$package->addMaintainer('thesaur',  'lead',        'Klaus Guenther',   'thesaur@php.net');
$package->addMaintainer('dufuz',   'developer',   'Helgi &#x00DE;ormar',      'dufuz@php.net');

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
