<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopCommunity\Tests\Integration\Internal\Framework\Module\Command;

use OxidEsales\EshopCommunity\Internal\Framework\Module\Command\ModuleActivateCommand;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Setup\Bridge\ModuleActivationBridgeInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Module\State\ModuleStateServiceInterface;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * @group template_dir
 * Class ModuleActivateCommandTest
 * @package OxidEsales\EshopCommunity\Tests\Integration\Internal\Framework\Module\Command
 */
final class ModuleActivateCommandTest extends ModuleCommandsTestCase
{

    public function testModuleActivation(): void
    {
        $consoleOutput = $this->execute(
            $this->getApplication(),
            $this->get('oxid_esales.console.commands_provider.services_commands_provider'),
            new ArrayInput(['command' => 'oe:module:activate', 'module-id' => $this->moduleId])
        );

        $this->assertSame(
            sprintf(ModuleActivateCommand::MESSAGE_MODULE_ACTIVATED, $this->moduleId) . PHP_EOL,
            $consoleOutput
        );

        $this->assertTrue(
            $this->get(ModuleStateServiceInterface::class)->isActive($this->moduleId, 1)
        );

    }

    public function testWhenModuleAlreadyActive(): void
    {
        $this->get(ModuleActivationBridgeInterface::class)->activate($this->moduleId, 1);

        $consoleOutput = $this->execute(
            $this->getApplication(),
            $this->get('oxid_esales.console.commands_provider.services_commands_provider'),
            new ArrayInput(['command' => 'oe:module:activate', 'module-id' => $this->moduleId])
        );

        $this->assertSame(
            sprintf(ModuleActivateCommand::MESSAGE_MODULE_ALREADY_ACTIVE, $this->moduleId) . PHP_EOL,
            $consoleOutput
        );

    }

    public function testNonExistingModuleActivation(): void
    {
        $moduleId = 'test';
        $consoleOutput = $this->execute(
            $this->getApplication(),
            $this->get('oxid_esales.console.commands_provider.services_commands_provider'),
            new ArrayInput(['command' => 'oe:module:activate', 'module-id' => $moduleId])
        );

        $this->assertSame(
            sprintf(ModuleActivateCommand::MESSAGE_MODULE_NOT_FOUND, $moduleId) . PHP_EOL,
            $consoleOutput
        );
    }
}
