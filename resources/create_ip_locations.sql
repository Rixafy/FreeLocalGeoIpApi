CREATE TABLE IF NOT EXISTS `ip_locations` (
    `geoname_id` int unsigned NOT NULL PRIMARY KEY,
    `locale_code` varchar(32) NOT NULL,
    `continent_code` char(2) NOT NULL,
    `continent_name` varchar(32) NOT NULL,
    `country_iso_code` char(2) NOT NULL,
    `country_name` varchar(64) NOT NULL,
    `subdivision_1_iso_code` varchar(3) NOT NULL,
    `subdivision_1_name` varchar(128) COLLATE 'utf8_unicode_520_ci' NOT NULL,
    `subdivision_2_iso_code` varchar(3) NOT NULL,
    `subdivision_2_name` varchar(128) COLLATE 'utf8_unicode_520_ci' NOT NULL,
    `city_name` varchar(128) COLLATE 'utf8_unicode_520_ci' NOT NULL,
    `metro_code` smallint unsigned NOT NULL,
    `time_zone` varchar(64) NOT NULL
);

CREATE TABLE IF NOT EXISTS `ip_locations_new` LIKE `ip_locations`;
