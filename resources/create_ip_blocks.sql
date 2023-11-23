CREATE TABLE IF NOT EXISTS `ip4_blocks` (
    `ip_from` int unsigned NOT NULL UNIQUE,
    `ip_to` int unsigned NOT NULL PRIMARY KEY,
    `network` varchar(32) NOT NULL,
    `geoname_id` int unsigned NOT NULL,
    `registered_country_geoname_id` int unsigned NOT NULL,
    `represented_country_geoname_id` int unsigned NOT NULL,
    `is_anonymous_proxy` tinyint(1) NOT NULL,
    `is_satellite_provider` tinyint(1) NOT NULL,
    `postal_code` varchar(32) NOT NULL,
    `latitude` float(8,4) NOT NULL,
    `longitude` float(8,4) NOT NULL,
    `accuracy_radius` smallint unsigned NOT NULL
);

CREATE TABLE IF NOT EXISTS `ip4_blocks_new` LIKE `ip4_blocks`;

CREATE TABLE IF NOT EXISTS `ip6_blocks` (
    `ip_from` binary(16) NOT NULL UNIQUE,
    `ip_to` binary(16) NOT NULL PRIMARY KEY,
    `network` varchar(32) NOT NULL,
    `geoname_id` int unsigned NOT NULL,
    `registered_country_geoname_id` int unsigned NOT NULL,
    `represented_country_geoname_id` int unsigned NOT NULL,
    `is_anonymous_proxy` tinyint(1) NOT NULL,
    `is_satellite_provider` tinyint(1) NOT NULL,
    `postal_code` varchar(32) NOT NULL,
    `latitude` float(8,4) NOT NULL,
    `longitude` float(8,4) NOT NULL,
    `accuracy_radius` smallint unsigned NOT NULL
);

CREATE TABLE IF NOT EXISTS `ip6_blocks_new` LIKE `ip6_blocks`;
