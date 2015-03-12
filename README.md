# prh-api
A simple PHP wrapper class for accessing the PRH API

##Installing demo
```
$ npm install && bower install
```
Change the value of ``const API_KEY`` in ``prh-api.php`` to your API key to enable the API calls.

Once enabled, you can make simple calls like this one, which is based on ISBN: 
```
$info = PRHAPI::get_title('9780307588364'); 
```
For more documentation, and to expand the class functions, check out the API documentation here: http://developer.penguinrandomhouse.com
