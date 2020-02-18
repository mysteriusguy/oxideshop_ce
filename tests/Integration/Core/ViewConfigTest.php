<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopCommunity\Tests\Integration\Core;

use OxidEsales\Eshop\Core\ViewConfig;
use OxidEsales\EshopCommunity\Tests\TestUtils\Traits\ModuleTestingTrait;
use PHPUnit\Framework\TestCase;

final class ViewConfigTest extends TestCase
{
    use ModuleTestingTrait;

    public function setUp()
    {
        parent::setUp();
        $this->backupModuleSetup();
    }

    public function tearDown()
    {
        $this->restoreModuleSetup();
        parent::tearDown();
    }

    public function testIsModuleActive(): void
    {
        $moduleId = 'with_metadata_v21';
        $this->installModule($moduleId);
        $this->activateModule($moduleId);

        $viewConfig = oxNew(ViewConfig::class);

        $this->assertTrue($viewConfig->isModuleActive($moduleId));
    }

}
