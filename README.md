# laika-php

laika-php is a PHP library that connects applications with the Laika feature flag service (https://github.com/MEDIGO/laika).
It is used for applications to know which flags are activated in the current environment and adapt to it.

## Setup

Add
```
"medigo/laika": "dev-master"
```
to composer.json and run
```
php composer.phar update
```

## How to use

When using Laika you need to configure in your environment:

- environment name - environment in which the code is being executed (e.g. "test" or "dev").
- url - url for the API server.
- username (optional) - username for the basic authentication.
- password (optional) - password for the basic authentication.

Initialize the library and get all the features from the database

```
public function __construct($environment, $host, $username, $password)
{
    $this->client = new Laika($environment, $host, $username, $password);
    $this->client->fetchAllFeatures();
    return $this->client;
}
```

Use the `isEnabled()` function to know whether the flag is activated or not in the current environment.

```
$myVar = 'the flag is disabled';
if ($laika->isEnabled('FLAG_NAME')) {
  $myVar = 'the flag is enabled';
}
```

## Test the library

Run

```
phpunit tests/LaikaTest.php
```

## Copyright and license

Copyright © 2017 MEDIGO GmbH.

Laika is licensed under the MIT License. See [LICENSE](LICENSE) for the full license text.