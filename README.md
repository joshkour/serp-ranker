# serp-ranker
Fetch ranking positions from SERP.

Using third party API DataForSeo.

https://docs.dataforseo.com/v2/srp#live-serp

- I have kept the cache directory with the cache files for testing purposes.
- Credentials should be stored in an ".env" files and fetched from there. For testing purposes I have included it in the file.

Design patterns that have been used:
1. MVC
Used to separate applications concerns. i.e Controllers / Model / Views.

2. Adapter
Using an adapter for the external API means if there are any changes to them, we can easily adapt to those changes.
When we need to make use of a different external API, we can easily create a new adapter and use it.

3. Proxy
Provides a proxy object that substitute for the real API for caching purposes.
It is used to check if we have a cache version stored before making the actual API request (providing better peformance).
I have went with a simple implementation for easy set up to make use of caching to file. However, this can be easily replaced with a different caching mechanism (memcache / redis).

4. Facade
Using a facade helps to remove complex subsystems from the client caller.
Used here to the hide the complex subsystem of fetching the data using the Proxy, extracting the results for today/weekly data.

## Requirements
- PHP Version (^7.1.3)
- Composer

## Setup
1. Git clone project: git clone https://github.com/joshkour/serp-ranker.git
2. Run in root path: composer install
3. Create apache vhost to point to directory (<PATH_TO_PROJECT>/public)
4. Go to http://127.0.0.1 (if set up as localhost)

## Unit test
Run in root path: php vendor/phpunit/phpunit/phpunit tests
