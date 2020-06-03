<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopCommunity\Internal\Framework\Storage;

use Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Filesystem\Exception\IOException;

class YamlFileStorage implements ArrayStorageInterface
{
    /**
     * @var FileLocatorInterface
     */
    private $fileLocator;

    /**
     * @var string
     */
    private $filePath;

    /**
     * @var LockFactory
     */
    private $lockFactory;

    /**
     * @var Filesystem
     */
    private $filesystemService;

    /**
     * YamlFileStorage constructor.
     * @param FileLocatorInterface $fileLocator
     * @param string               $filePath
     * @param LockFactory          $lockFactory
     * @param Filesystem           $filesystemService
     */
    public function __construct(
        FileLocatorInterface $fileLocator,
        string $filePath,
        LockFactory $lockFactory,
        Filesystem $filesystemService
    ) {
        $this->fileLocator = $fileLocator;
        $this->filePath = $filePath;
        $this->lockFactory = $lockFactory;
        $this->filesystemService = $filesystemService;
    }

    /**
     * @return array
     */
    public function get(): array
    {
        $fileContent = file_get_contents($this->getLocatedFilePath());

        $yaml = Yaml::parse(
            $fileContent
        );

        return $yaml ?? [];
    }

    /**
     * @param array $data
     */
    public function save(array $data): void
    {
        $lock = $this->lockFactory->createLock($this->getLockId());

        if ($lock->acquire(true)) {
            try {
                file_put_contents(
                    $this->getLocatedFilePath(),
                    Yaml::dump($data, 10, 2)
                );
            } finally {
                $lock->release();
            }
        }
    }

    /**
     * @return string
     */
    private function getLocatedFilePath(): string
    {
        try {
            $filePath = $this->fileLocator->locate($this->filePath);
        } catch (FileLocatorFileNotFoundException $exception) {
            $this->createFileDirectory();
            $this->createFile();
            $filePath = $this->fileLocator->locate($this->filePath);
        }

        return $filePath;
    }

    /**
     * Creates file directory if it doesn't exist.
     */
    private function createFileDirectory(): void
    {
        if (!$this->filesystemService->exists(\dirname($this->filePath))) {
            $this->filesystemService->mkdir(\dirname($this->filePath));
        }
    }

    /**
     * Creates file.
     */
    private function createFile(): void
    {
        $files = $this->filePath;
        $time = null;
        $atime = null;
        foreach ($this->toIterable($files) as $file) {
            $touch = $time ? touch($file, $time, $atime) : touch($file);
            if (true !== $touch) {
                exec('ls -al ' . dirname($this->filePath), $output);
                var_dump($output);
                $permissionsDir = decoct(fileperms(\dirname($this->filePath)) & 0777);
                $processUser = exec('id', $outputId);
                var_dump($touch, $outputId);
                throw new IOException(sprintf('Failed to touch "%s", with permissions "%s" for user %s.', $file, $permissionsDir, $processUser), 0, null, $file);
            } else {
                exec('ls -al ' . dirname($this->filePath), $output);
                var_dump($output);
                $permissionsDir = decoct(fileperms(\dirname($this->filePath)) & 0777);
                exec('id', $outputId);
                var_dump($touch, $permissionsDir, $outputId);
            }
        }
    }

    private function toIterable($files): iterable
    {
        return \is_array($files) || $files instanceof \Traversable ? $files : [$files];
    }

    /**
     * @return string
     */
    private function getLockId(): string
    {
        return md5($this->filePath);
    }
}
