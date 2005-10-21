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

$version = '1.6.0';

$notes = <<<EOT
- Mark Wieseman is now a active developer
- td/th aren't longer case sensitive in addRow() and relative functions
- Added the possibility to specify on which row the cols should be counted. (Bertrand)
- #786, if the value was a zero, the cell content was converted to the autofill value. (Bertrand)
- #1734, _adjustEnd added a extra empty column if there was only one column being processed
- setHeaderContents can now accept attributes, but it's optional. request #2030
- Request #4944: setCellContents() now accepts an array as \$contents (\$col will be used as the start column then).
- Request #4988: addRow() accepts now array keys as column numbers.
- Added support for thead, tfoot and tbody on the courtesy of Mark Wiesemann <wiesemann@php.net>

Usage:

- current behaviour is still available:
\$table = new HTML_Table();
\$table->setCellContents(...);
echo \$table->toHtml();

- new alternative with same result:
\$table = new HTML_Table();
\$body =& \$table->getBody();
\$body->setCellContents(...);
echo \$table->toHtml();

- using the new grouping:
\$table = new HTML_Table(null, null, true);
\$head =& \$table->getHeader();
\$foot =& \$table->getFooter();
\$body =& \$table->getBody();
\$head->setCellContents(...);
\$body->setCellContents(...);
echo \$table->toHtml();  // <tfoot> will not be rendered
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
