<?php
header('Content-Type: text/xml');
require 'inc/config.php';

$siteIdentities = $configurations ->findConfigs();
$Identities = $siteIdentities['results'];

foreach ($Identities as $identity) {
  $site_title = $identity['site_name'];
}

$createPostFeed = $postFeeds -> generatePostFeed($site_title, APP_DIR); 