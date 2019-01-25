# Centova Digester

This will digest an historical listener file from Centova Cast

## Installation

Can't be installed via composer. You should add this repository in your composer file:

```json
"repositories": [
        {
            "type": "git",
            "url": "https://github.com/comeul/centova-digester.git"
        }
    ],
```

And add the package as a dependency in the `composer.json` file like so:

```json
"comeul/centova-digester": "dev-master@dev"
```

The package might run without Laravel, but it's not sure, as it's intended to be used within a Laravel Install.

Note that you shuld also install the [Laravel-Excel](https://github.com/Maatwebsite/Laravel-Excel) package to your Laravel installation for it to work fine.

This package is not sexy, there is no test, this was a quick fix to an internal demand.

## Usage

```php
$digester = new CentovaStatsDigester($filepath); // send the path to the csv file
$digester->exportFile("CentovaStats-01Jan2017-25Mai-2017"); // this is the name of the excel file that'll be sent
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
