### Guzzle File Mock

Guzzle Mocking of Http calls to file system. On first call creates a snapshot and uses it afterwords.

```
	$client = new GuzzleFileMock([
	            'file_mock' => __DIR__ . '/snapshots/',
	            'base_uri' => 'https://some.endpoint.org/'
	        ])

	$client->post("users", [
	            "form_params" => ["name" => "Peter"]
	        ]);
```