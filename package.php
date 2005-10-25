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

$version = '1.6.1';

$notes = <<<EOT
- Bugfix: addCol() must not call Storage::updateRowAttributes(), but Storage::addCol()
- Bugfix: addCol() had the same problem as in request #4988 (it did not accept array keys as row numbers)
- Bugfix: toHtml() wrongly assumed that there are instances of \$_thead and \$_tfoot when \$useTGroups == true
- Bugfix: return PEAR_Error object in getCellContents when cell does not exist
- Bug #5782: remove PHP warning if \$contents == null in addCol() and addRow()
- Couple of unit tests were added, covering a lot of ground.
EOT;

$description = <<<EOT
The PEAR::HTML_Table package provides methods for easy and efficient design of HTML tables.
 - Lots of customization options.
 - Tables can be modified at any time.
 - The logic is the same as standard HTML editors.
 - Handles col and rowspans.
 - PHP code is shorter, easier to read and to maintain.
 - Tables options can be reused.

For auto filling of data and such then check out http://pear.php.net/package/HTML_Table_Matrix
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

$package->addMaintainer('mansion',   'lead',      'Bertrand Mansion',  'bmansion@mamasam.com');
$package->addMaintainer('thesaur',   'lead',      'Klaus Guenther',    'thesaur@php.net');
$package->addMaintainer('dufuz',     'lead',      'Helgi &#222;ormar', 'dufuz@php.net');
$package->addMaintainer('wiesemann', 'developer', 'Mark Wiesemann',    'wiesemann@php.net');

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
