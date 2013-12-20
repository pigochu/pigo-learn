<?php
/**
 * @see http://www.regular-expressions.info/numericranges.html
 */
error_reporting(E_ALL);
$numerics = array(
		'000',
		'255',
		'256',
		'0',
		'01',
		'-1',
		'+99',
		'99',
		'88',
		'-99',
		'-555',
		'123',
		'1000',
);

$pattern = '/^([01][0-9][0-9]|2[0-4][0-9]|25[0-5])$/';
echo "Check 000..255\n";
foreach($numerics as $numeric) {
	echo "Numeric $numeric is " . (preg_match($pattern, $numeric, $matches) ? 'true' : 'false') . "\n";	
}

$pattern = '/^([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])$/';
echo "Check 0 or 000..255\n";
foreach($numerics as $numeric) {
	echo "Numeric $numeric is " . (preg_match($pattern, $numeric, $matches) ? 'true' : 'false') . "\n";
}

$pattern = '/^([0-9]|[1-9][0-9]|[1-9][0-9][0-9])$/';
echo "Check 0..999\n";
foreach($numerics as $numeric) {
	echo "Numeric $numeric is " . (preg_match($pattern, $numeric, $matches) ? 'true' : 'false') . "\n";
}

$pattern = '/^([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])$/';
echo "Check 0..255\n";
foreach($numerics as $numeric) {
	echo "Numeric $numeric is " . (preg_match($pattern, $numeric, $matches) ? 'true' : 'false') . "\n";
}

$pattern = '/^([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])$/';
echo "Check 0 or 000..255\n";
foreach($numerics as $numeric) {
	echo "Numeric $numeric is " . (preg_match($pattern, $numeric, $matches) ? 'true' : 'false') . "\n";
}



