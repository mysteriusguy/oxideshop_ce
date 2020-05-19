<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopCommunity\Tests\Integration\Internal;

use OxidEsales\Eshop\Core\ViewConfig;
use OxidEsales\EshopCommunity\Tests\TestUtils\Traits\ModuleTestingTrait;
use PHPUnit\Framework\TestCase;
use Webmozart\PathUtil\Path;

final class ViewConfigTest extends TestCase
{
    use ModuleTestingTrait;

    public function setup(): void
    {
        parent::setUp();
        $this->setupIntegrationTest();
    }

    public function tearDown(): void
    {
        $this->tearDownTestContainer();
        parent::tearDown();
    }

    public function testIsModuleActive(): void
    {
        $moduleId = 'with_metadata_v21';
        $this->installModule($moduleId, Path::canonicalize(Path::join(__DIR__, 'Module', 'Fixtures')));
        $this->activateModule($moduleId);

        $viewConfig = oxNew(ViewConfig::class);

        $this->assertTrue($viewConfig->isModuleActive($moduleId));
    }

}
