# Commission Calculator


## 1. Setup

Ideally, run the project under Docker. To do so, run:

`docker-compose up`

Once the container starts, you can access the project by following the below steps:

* Run `docker container exec -it task-app-1 bash`
* Navigate to `/var/www`

---

If Docker is not available, your environment must have the following for the script to work:
* PHP 8.1.x (8.2 will not work as CS-Fixer still does not support it)
* cUrl PHP Extension

---

In both cases, run `php composer.phar install`.


## 2. Running Script

To run the script, follow the below command format:

```php script.php <csv_data_source> <currencies_url>```

Where

* `csv_data_source` corresponds to the file location of the operations CSV to be read
* `currencies_url` corresponds to a URL returning a valid currencies JSON. 

**Example**

```
php script.php test.csv https://my.currencies.dev
```

### Operations CSV Format

The operations CSV **must** follow the below format:
Each CSV row must hold the following data:
* Date in `Y-m-d` format
* User Id as an integer
* User Type (`private` or `business`)
* User Operation (`deposit` or `withdraw`)
* Amount as double
* Currency (e.g. `EUR`, `USD`) etc.

**Example**
```csv
2014-12-31,4,private,withdraw,1200.00,EUR
```

No newlines should follow the last line.

### Currencies JSON Format

The currencies JSON **must** follow the below format:

```json
{
    "base": "EUR",
    "date": "2023-02-18",
    "rates": {
        "EUR": 1,
        "JPY": 129.53,
        "USD": 1.1497
    }
}
```

## 3. Running Tests

Tests and other scripts are defined under `composer.json`. To run them, use the following format:

`php composer.phar <script_name>`

The currently available commands are as follows;

* `phpunit` : Runs the unit tests for the projects.
* `fix-cs` : Fixes any code style issues found
* `test-cs` : Dry-run test aiming to find code style issues
* `test` : A test command that combines the functionality of `phpunit` and `test-cs`

## 4. Automation Test

It can be found under `CommissionCalculator\Tests\Service\CommissionCalculatorTest::testCSVInputProducesDesiredResults()`.

It is run with the other unit tests when running `php composer.phar phpunit`

Some expected results have been changed as I could not find any way to reproduce them. I have marked them within the class's `$expectedResults` property.

## 5. Workflow

The script uses a generator to print out a calculated commission for every valid CSV row. This generator is called with `CommissionCalculator::generateCommission()`

The CSV rows themselves are formalized within the `OperationData` class which acts both as a data structure and a validator.

Iterating through the CSV expects that the data source implements the `Iterator` interface. In this case, this is done by the
`CSVIterator` but any other Iterator should do as well. The only concrete requirement at the moment is that
every data row **must** follow both the order and format as defined above in **Operations CSV Format**.

The associated user and type of operation are mapped to a class and method (if existent). Said class lives under the `CommissionCalculator\Service\Users` namespace.
Every public method within the class is expected to return a calculated commission based on the user and operation specifics.
This segregation allows for an independent implementation of each user and operation.

An `OperationRegistry` keeps track of past users and operations. Data is kept in the following format:
```
[
    user_id_1 => [
        operation_type_1 => [
            OperationData::class
            OperationData::class
            ...
        ],
        operation_type_2 => [
            OperationData::class
            ...
        ]
        ...
    ]
    user_id_2 => [
        ...
    ]
    ...
]
```

This allows for a somewhat easy and fast indexing and subsequent recall of data.



