LOAD DATA LOCAL INFILE '{filePath}' INTO TABLE `ip4_blocks_new` COLUMNS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"' IGNORE 1 LINES (
    @network,
    geoname_id,
    registered_country_geoname_id,
    represented_country_geoname_id,
    is_anonymous_proxy,
    is_satellite_provider,
    postal_code,
    latitude,
    longitude,
    accuracy_radius) SET
    ip_from = INET_ATON(SUBSTRING(@network, 1, LOCATE('/', @network) - 1)),
    ip_to = (INET_ATON(SUBSTRING(@network, 1, LOCATE('/', @network) - 1)) + (pow(2, (32-CONVERT(SUBSTRING(@network, LOCATE('/', @network) + 1), UNSIGNED INTEGER)))-1));
RENAME TABLE `ip4_blocks` TO `ip4_blocks_old`, `ip4_blocks_new` TO `ip4_blocks`;
DROP TABLE `ip4_blocks_old`;
