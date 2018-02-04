BinaryUtilities [![Build Status](https://travis-ci.org/pburggraf/BinaryUtilities.svg?branch=master)](https://travis-ci.org/pburggraf/BinaryUtilities) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/pburggraf/BinaryUtilities/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/pburggraf/BinaryUtilities/?branch=master)
===

Class for working with binary data in PHP >=7.1

## How to use
```PHP
<?php

require __DIR__ . '/vendor/autoload.php';

use \PBurggraf\BinaryUtilities\BinaryUtilityFactory;
use \PBurggraf\BinaryUtilities\DataType\Byte;

file_put_contents('/tmp/temp.txt', '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');

$binaryUtility = BinaryUtilityFactory::create();

// Read data
$result = $binaryUtility
    ->setFile('/tmp/temp.txt')
    ->offset(0x08)
    ->readArray(Byte::class, 4)
    ->returnBuffer();

var_dump($result);
// Expected result:
// array(4) {
//     [0] =>
//   string(2) "56"
//     [1] =>
//   string(2) "57"
//     [2] =>
//   string(2) "65"
//     [3] =>
//   string(2) "66"
// }

// Write data
$binaryUtility
    ->offset(0x08)
    ->writeArray(Byte::class, [66, 65, 57, 56])
    ->save();

var_dump(file_get_contents('/tmp/temp.txt'));
// Expected result:
// string(36) "01234567BA98CDEFGHIJKLMNOPQRSTUVWXYZ"

```
