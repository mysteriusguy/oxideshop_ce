<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Framework\Logger\Factory;

use OxidEsales\EshopCommunity\Internal\Framework\Logger\Configuration\MonologConfiguration;
use OxidEsales\EshopCommunity\Internal\Framework\Logger\Factory\MonologLoggerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Logger\Validator\PsrLoggerConfigurationValidator;
use OxidEsales\EshopCommunity\Tests\TestUtils\IntegrationTestCase;
use OxidEsales\EshopCommunity\Tests\Unit\Internal\ContextStub;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class MonologFactoryTest extends IntegrationTestCase
{
    public function testCreation()
    {
        $context = new ContextStub();

        $configuration = new MonologConfiguration(
            'testLogger',
            $context->getLogFilePath(),
            $context->getLogLevel()
        );

        $validator = new PsrLoggerConfigurationValidator();

        $loggerFactory = new MonologLoggerFactory(
            $configuration,
            $validator
        );

        $this->assertInstanceOf(
            LoggerInterface::class,
            $loggerFactory->create()
        );
    }
}
