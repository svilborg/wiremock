## WireMock

[WireMock](https://wiremock.org/docs/overview/) is a open-source tool for API mock testing.

### Install

`docker compose up wiremock`

WireMock is available on `http://0.0.0.0:8888/__admin/mappings/`

### Running the Experiments

`docker compose -f ./docker-compose.yml exec wiremockapp bash`

`php ./src/sample.php`

Uses `wiremock-php/wiremock-php` to control/record WireMock Server trough the `__admin` API.

### Pros/Cons

**Pros**

- Centralized mocks storage on local/test/beta for any 3rd party API or internal ones - ocular, for example
- WireMock Admin API to record mocks; Record Snapshots - using WireMock as proxy for recording
- Supports mocks in json format (WireMock mappings)
- Supports delays - static/random, etc.
- Supports errors - empty/malformed data/closed connection
- Uses http://handlebarsjs.com/ for templates/variables
- Request Headers/Params can be used to inject dynamic content/values
- Support for JSON/Graphql/XML/SOAP
- Keeps info about all received requests/ missed matches for debugging
- Library with mocks - https://library.wiremock.org/
- Could be used between Pepper projects, Pepper APP Mocks for APPs or Pepper Business

**Cons**

- Another tool
- Another docker dependency
- If used cross project - another repo / supporting mappings
- It is not very easy to work with `wiremock-php` - no documentation, but it is a fork of the java one
- Needs reset after manually editing a mock (`curl -X POST http://0.0.0.0:8888/__admin/mappings/reset`)
- No free official UI, cloud version only, there are some unofficial ones
- No free IntelliJ/PHPStorm plugin; Free plugin for Visual Studio Code but I cant quite work with it
- JSON responses (usually) inside JSON mapping - not easy

### Examples

##### Recording mocks / mappings

[Recording](./src/record/recording.php)

[Snapshot](./src/record/snapshot_post.php)

##### Exact Url matching

```php
GET http://0.0.0.0:8888/123
```

```http request

HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8
Date: Sat, 14 Oct 2023 10:52:34 GMT
Matched-Stub-Id: 8b830f63-9592-49c5-940b-2033b55f8ccd
Matched-Stub-Name: 123

{
"message": "Sample by URL",
"status": "ok"
}
```

##### Pattern Url matching

```php
GET http://0.0.0.0:8888/sample/abc
```

```http request

HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8
Matched-Stub-Id: 8b830f63-9592-49c5-940b-2033b55f8888
Matched-Stub-Name: sample-match

{
    "message": "Sample by URL Pattern",
    "status": "ok"
}
```

##### Variables in response - random alphanumeric

```php
GET http://0.0.0.0:8888/random
```

```http request
HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8
Date: Sat, 14 Oct 2023 10:52:34 GMT
Matched-Stub-Id: 8b830f63-9592-49c5-940b-2033b55f1111
Matched-Stub-Name: sample-random

{
    "created": 1697552476,
    "message": "Sample random string: h0hcobts1yghuvibpkk9rctrlyg7x8a67",
    "status": "ok"
}

```

##### Handlebars templates

```php
GET http://0.0.0.0:8888/param?id=123
```

```http request
HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{
"message": "Called with: 123 ",
"number": "YES"
}
```

```php
GET http://0.0.0.0:8888/param?id=abc
```

```http request
HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{
"message": "Called with: abc ",
"number": "NO"
}
```

##### No Match

```php
GET http://0.0.0.0:8888/none
```

```http request

HTTP/1.1 404 Not Found
Content-Type: text/plain;charset=UTF-8

                                               Request was not matched
                                               =======================

-----------------------------------------------------------------------------------------------------------------------
| Closest stub                                             | Request                                                  |
-----------------------------------------------------------------------------------------------------------------------
                                                           |
Get models                                                 |
                                                           |
GET                                                        | GET
[path template] /v1/models                                 | /none                        <<<<< URL does not match
                                                           |
-----------------------------------------------------------------------------------------------------------------------

```
