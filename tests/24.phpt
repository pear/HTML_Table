--TEST--
24.phpt: thead, tfoot and addRow (tbody not in output)
--FILE--
<?php
// $Id$
require_once 'HTML/Table.php';
$table =& new HTML_Table();

$thead =& $table->getHeader();
$tfoot =& $table->getFooter();

$data[0][] = 'Test';
$data[1][] = 'Test';

foreach($data as $key => $value) {
    $thead->addRow($value);
    $tfoot->addRow($value);
}

// output
echo $table->toHTML();
?>
--EXPECT--
<table>
	<thead>
		<tr>
			<td>Test</td>
		</tr>
		<tr>
			<td>Test</td>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td>Test</td>
		</tr>
		<tr>
			<td>Test</td>
		</tr>
	</tfoot>
</table>