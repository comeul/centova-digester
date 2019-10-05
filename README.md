# Centova Digester

This is an internally used tool used to digest a Centova listener file.

It can by anybody who has access to a Centova Cast listener file, exported from their admin page. This will digest the data and return an excel file that's easier to read for non tech people.

This package was always though to be ran in a Laravel instance but it would probably still run in a vanilla PHP environment, although we give no guarantee for that.

This package is not sexy, there is no test, but it works.

## Installation

Since this is not really ready for wide distribution, the package isn't distributed via Composer, so you will have to add this repo in your `composer.json` file.

```json
"repositories": [
        {
            "type": "git",
            "url": "https://github.com/comeul/centova-digester.git"
        }
    ],
```

Then you should add the package as a dependency in the `composer.json` file like so:

```json
"comeul/centova-digester": "dev-master@dev"
```

You will also have to install the  [Laravel-Excel](https://github.com/Maatwebsite/Laravel-Excel) for this package to work properly.

## Usage

```php
$digester = new CentovaStatsDigester($filepath); // send the path to the csv file
$digester->exportFile("CentovaStats-01Jan2017-25Mai-2017"); // this is the name of the excel file that'll be sent
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
