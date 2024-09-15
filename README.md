# Free Local Geo-IP PHP Api
Reliable and fast local geo-ip api based on MaxMind geoip2 database.

Database can be scheduled (set up a cron) to be updated every day with `bin/update` command.

## Requirements
- PHP 8.1+
- Composer
- MySQL / MariaDB database

## Installation
1. Download this project and run `composer install` to install dependencies.
2. Register at [maxmind.com](https://www.maxmind.com/en/geolite2/signup) and get your free geolite2 license key and account id.
3. Copy `config.sample.php` to `config.php` and set your license key, account id and database credentials.
4. Make sure you are not using tables `ip4_blocks`, `ip6_blocks` and `ip_locations` as they will be overwritten.
5. Run `bin/update` to download and import the database to your local db (~400MB).
5. Open a web server (for instance `php -S localhost:1234 ./public/index.php`)
6. Make a request to `http://localhost:1234?ip=<ip>`

Feel free to dockerize this project or make PR with more customization options.

## Example response

From url `http://localhost:1234/?ip=8.8.8.8`

```json
{
  "continent_code": "NA",
  "continent_name": "North America",
  "country_iso_code": "US",
  "country_name": "United States",
  "city_name": null,
  "subdivision_1_iso_code": null,
  "subdivision_1_name": null,
  "subdivision_2_iso_code": null,
  "subdivision_2_name": null,
  "metro_code": null,
  "time_zone": "America/Chicago",
  "latitude": 37.751,
  "longitude": -97.822,
  "is_anonymous_proxy": false
}
```
