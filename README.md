### Guzzle File Mock

[![Build Status](https://api.travis-ci.org/svilborg/guzzle-file-mock.svg?branch=master)](https://travis-ci.org/svilborg/guzzle-file-mock)
[![Latest Stable Version](https://img.shields.io/packagist/v/svilborg/guzzle-file-snapshot.svg)](https://packagist.org/packages/svilborg/guzzle-file-snapshot)
[![License](https://img.shields.io/packagist/l/svilborg/guzzle-file-snapshot.svg)](https://github.com/svilborg/guzzle-file-snapshot/blob/master/LICENSE)

Guzzle Mocking of Http calls to file system. On first call creates a snapshot and uses it afterwords.

### Usage

```php
$client = new GuzzleFileMock([
	            'file_mock' => __DIR__ . '/snapshots/',
	            'base_uri' => 'https://some.endpoint.org/'
	            ]);

$client->post("users", [
	            "form_params" => ["name" => "Peter"]
	        ]);
```

Php serializer and extension :

```php
$client = new GuzzleFileMock([
	            'file_mock' => __DIR__ . '/snapshots/',
	            'file_mock_ext' => 'txt',
	            'file_mock_serializer' => '\GuzzleHttpMock\Serializer\PhpSerializer',
	            'base_uri' => 'https://some.endpoint.org/'
	            ]);

$client->post("users", [
	            "form_params" => ["name" => "Peter"]
	        ]);
```
