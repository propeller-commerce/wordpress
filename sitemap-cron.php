<?php

include_once './../../../wp-load.php';

require plugin_dir_path(__FILE__) . '/vendor/autoload.php';

set_time_limit(0);

$sitemap = new \Propeller\PropellerSitemap();

$sitemap->build_sitemap();