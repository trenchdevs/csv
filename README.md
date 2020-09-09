# trenchdevs/csv

A simple csv wrapper for box/spout package

## Installation 

Install via composer 

`composer require trenchdevs/csv`

## Usage

### Reading Files 

`tests/testfiles/valid.csv`

```
id,name,age, tag line,extraheader
1,george,33, "this is my multi line tagline"
2,"Georgie Smith", 31,
```

```php

use TrenchDevs\Csv\CsvReader;

try {
    
    $reader = new CsvReader('/full/file/path'); // eg. __DIR__ . '/tests/testfiles/valid.csv'
    
    // set the header columns as the headers, use these as the keys for the results while on iteration  
    $reader->setFirstRowAsHeaders(true);
    
    // optional: override headers string, by default it will use columns 
    // on csv if setFirstRowAsHeaders is set to true
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

```bash

chris@chriss-MacBook-Pro csv % ./vendor/bin/phpunit tests --testdox
PHPUnit 9.3.8 by Sebastian Bergmann and contributors.

Csv Reader
 ✔ Constructor empty file path test
 ✔ Constructor invalid file path test
 ✔ Constructor valid file test
 ✔ Can iterate items with first row as headers
 ✔ Can iterate items
 ✔ Can override headers test
 ✔ Can override headers first row as headers false test

Time: 00:00.009, Memory: 4.00 MB

OK (7 tests, 41 assertions)

```

## License 

The trenchdevs/csv library is open-sourced software under the [MIT License](https://github.com/trenchdevs/csv/blob/master/LICENSE) 