<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Integration\Core\Module;

use OxidEsales\Eshop\Core\UtilsView;
use OxidEsales\EshopCommunity\Tests\TestUtils\Traits\ModuleTestingTrait;
use PHPUnit\Framework\TestCase;
use Webmozart\PathUtil\Path;

/**
 * Class ModuleSmartyPluginDirectoryTest
 */
class ModuleSmartyPluginDirectoriesTest extends TestCase
{
    use ModuleTestingTrait;

    public function setup(): void
    {
        parent::setUp();
        $this->setupIntegrationTest();
        $module = 'with_metadata_v21';
        $this->installModule($module, Path::canonicalize(Path::join(__DIR__, 'Fixtures')));
        $this->activateModule($module);

    }

    public function tearDown(): void
    {
        $this->tearDownTestContainer();
        parent::tearDown();
    }

    /**
     * Smarty should know about the smarty plugin directories of the modules being activated.
     */
    public function testModuleSmartyPluginDirectoryIsIncludedOnModuleActivation()
    {
        $utilsView = oxNew(UtilsView::class);
        $smarty = $utilsView->getSmarty(true);

        $this->assertTrue(
            $this->isPathInSmartyDirectories($smarty, 'Smarty/PluginDirectory1WithMetadataVersion21')
        );

        $this->assertTrue(
            $this->isPathInSmartyDirectories($smarty, 'Smarty/PluginDirectory2WithMetadataVersion21')
        );
    }

    public function testSmartyPluginDirectoriesOrder()
    {
        $utilsView = oxNew(UtilsView::class);
        $smarty = $utilsView->getSmarty(true);

        $this->assertModuleSmartyPluginDirectoriesFirst($smarty->plugins_dir);
        $this->assertShopSmartyPluginDirectorySecond($smarty->plugins_dir);
    }

    private function assertModuleSmartyPluginDirectoriesFirst($directories)
    {
        $this->assertStringContainsString(
            'Smarty/PluginDirectory1WithMetadataVersion21',
            $directories[0]
        );

        $this->assertStringContainsString(
            'Smarty/PluginDirectory2WithMetadataVersion21',
            $directories[1]
        );
    }

    private function assertShopSmartyPluginDirectorySecond($directories)
    {
        $this->assertStringContainsString(
            'Core/Smarty/Plugin',
            $directories[2]
        );
    }

    private function isPathInSmartyDirectories($smarty, $path)
    {
        foreach ($smarty->plugins_dir as $directory) {
            if (strpos($directory, $path)) {
                return true;
            }
        }

        return false;
    }

}
