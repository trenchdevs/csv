# trenchdevs/csv

A simple csv wrapper for box/spout package

## Installation 

Install via composer 

`composer require trenchdevs/csv`

## Usage

### Reading Files 

```php

use TrenchDevs\Csv\CsvReader;

try {
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
        //  $row['age'], $row['tagline'],      
    }

} catch (Exception $exception ) {
    // handle
}

```

### Reading and Writing on the same file 

Coming soon... 

## Testing 

`./vendor/bin/phpunit tests --testdox`

## License 

trenchdevs/csv is open-sourced software under the [MIT License](https://github.com/trenchdevs/csv/blob/master/LICENSE) 