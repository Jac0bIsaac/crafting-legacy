<?php

require 'inc/config.php';

$siteIdentities = $configurations ->findConfigs();
$Identities = $siteIdentities['results'];

foreach ($Identities as $identity) {
  $site_title = $identity['site_name'];
  $tagline = $identity['tagline'];
}

$createPostFeed = $postFeeds -> generatePostFeed($site_title, APP_DIR, $tagline); 