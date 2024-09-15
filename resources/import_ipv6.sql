LOAD DATA LOCAL INFILE '{filePath}' INTO TABLE `ip6_blocks_new` COLUMNS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"' IGNORE 1 LINES (
    network,
    @ip_from,
    @ip_to,
    geoname_id,
    registered_country_geoname_id,
    represented_country_geoname_id,
    is_anonymous_proxy,
    is_satellite_provider,
    postal_code,
    latitude,
    longitude,
    accuracy_radius
) SET
    ip_from = INET6_ATON(@ip_from),
    ip_to = INET6_ATON(@ip_to);
RENAME TABLE `ip6_blocks` TO `ip6_blocks_old`, `ip6_blocks_new` TO `ip6_blocks`;
DROP TABLE `ip6_blocks_old`;
