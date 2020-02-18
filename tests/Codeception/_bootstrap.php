<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

require_once \Webmozart\PathUtil\Path::join(__DIR__, '..', 'bootstrap.php');

$filesystem = new \Symfony\Component\Filesystem\Filesystem();

if (! $filesystem->exists(\Webmozart\PathUtil\Path::join(OX_BASE_PATH, 'config.inc.php'))) {
    $filesystem->copy(\Webmozart\PathUtil\Path::join(OX_BASE_PATH, 'config.inc.php.dist'),
        \Webmozart\PathUtil\Path::join(OX_BASE_PATH, 'config.inc.php'));
}
