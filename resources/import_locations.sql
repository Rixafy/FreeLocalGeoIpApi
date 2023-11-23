LOAD DATA LOCAL INFILE '{filePath}' INTO TABLE `ip_locations_new` CHARACTER SET UTF8 COLUMNS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"' IGNORE 1 LINES (
    geoname_id,
    locale_code,
    continent_code,
    continent_name,
    country_iso_code,
    country_name,
    subdivision_1_iso_code,
    subdivision_1_name,
    subdivision_2_iso_code,
    subdivision_2_name,
    city_name,
    metro_code,
    time_zone
);
RENAME TABLE `ip_locations` TO `ip_locations_old`, `ip_locations_new` TO `ip_locations`;
DROP TABLE `ip_locations_old`;
