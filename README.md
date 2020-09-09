# trenchdevs/csv

A simple csv wrapper for box/spout package

## Usage

### Reading Files 

```php

use TrenchDevs\Csv\CsvReader;

// eg. tests/testfiles/valid.csv
$reader = new CsvReader('full/file/path');

// set the headers as the headers, use these as the keys 
// for the results while on iteration  
$reader->setFirstRowAsHeaders(true);

// optional: override headers string
$reader->setHeaders(['id', 'name', 'age', 'tagline']);

foreach ($reader->iterator() as $row) {
    // do something with $row
    //  $row['id'], $row['name'], 
    //  $row['age'],$row['tagline'],      
}

```

### Reading and Writing on the same file 

Coming soon... 

## Testing 

`./vendor/bin/phpunit tests --testdox`

## License 

MIT 