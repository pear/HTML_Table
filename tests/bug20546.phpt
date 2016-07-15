--TEST--
bug #20546: Undefined index: attr when calling getCellAttributes on cell without attributes
--FILE--
<?php
// $Id$
require_once 'HTML/Table.php';
$table = new HTML_Table();

$table->addRow(array('dummy'));
var_dump($table->getCellAttributes(0, 0));
?>
--EXPECT--
array(0) {
}
