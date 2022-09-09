# GrizzIT - Micro Router

A minimalist router for projects that require a small footprint.

## Adding routes
To register route file locations, add the following line:
```php
<?php

use GrizzIt\MicroRouter\Http\Router;

Router::loadRoutesFromFile('path/to/routes/directory'));
```

In this directory create a `routes.yaml` file and fill it with some routes:

```yaml
home_route:
  path: /{id}
  methods: GET
  controller: my_controller::home
```

For more in depth information on how routes can be defined, see
[the Symfony docs](https://symfony.com/doc/current/routing.html#creating-routes-in-yaml-xml-or-php-files).

This package currently only support YAML definitions.

The parameters e.g. `{id}` can be accessed on the request object, by using the 
`get` method.

```php
<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

function home(Request $request): Response
{
    return json_response(['id' => $request->get('id')]);
}
```

The exemption to the class for the `controller` is that the class of the 
controller should be used, but instead the service definition key of the
controller. See [grizz-it/micro-service](https://github.com/grizz-it/micro-service) on how this works.

## Running the router

To run the router, use the following snippet:

```php
<?php

use GrizzIt\MicroRouter\Http\Router;

Router::run()->send();
```

## Creating responses

This package also contains a helper function to quickly generate
JSON responses. This can be used through the following snippet:
```php
json_response(['type' => 'home']);
```

The first parameter can be any form of data which will be automatically encoded 
into JSON. The second parameter is an optional response code (default: 200).
And finally the third parameter are optional additional headers (besides the Content-Type).

## MIT License

Copyright (c) GrizzIT

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.