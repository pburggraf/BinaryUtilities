<?php

declare(strict_types=1);

namespace PBurggraf\BinaryUtilities\Test;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PBurggraf\BinaryUtilities\BinaryUtilities;
use PBurggraf\BinaryUtilities\BinaryUtilityFactory;
use PBurggraf\BinaryUtilities\DataType\Byte;
use PBurggraf\BinaryUtilities\DataType\Integer;
use PBurggraf\BinaryUtilities\Exception\FileDoesNotExistsException;
use PHPUnit\Framework\TestCase;

/**
 * @author Philip Burggraf <philip@pburggraf.de>
 */
class BinaryUtilitiesTest extends TestCase
{
    /**
     * @var string
     */
    protected $binaryFile;

    /**
     * @var vfsStreamDirectory
     */
    protected $virtualFileSystem;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->virtualFileSystem = vfsStream::setup();
        $virtualFile = vfsStream::newFile('data.bin')
            ->at($this->virtualFileSystem)
            ->setContent(base64_decode('ABEiM0RVZneImaq7zN3u///u3cy7qpmId2ZVRDMiEQABAgQIECBAgA=='));

        $this->binaryFile = $virtualFile->url();
    }

    /**
     * @throws FileDoesNotExistsException
     */
    public function testReadNonExistingFile()
    {
        $this->expectException(FileDoesNotExistsException::class);

        $binaryUtility = BinaryUtilityFactory::create();
        $binaryUtility->setFile('nonExistingFile.bin');
    }

    public function testBinaryBaseMethod()
    {
        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($this->binaryFile)
            ->offset(0x10)
            ->read(Integer::class)
            ->setBase(BinaryUtilities::BASE_BINARY)
            ->returnBuffer();

        static::assertCount(1, $byteArray);
        static::assertEquals(['11111111111011101101110111001100'], $byteArray);
    }

    public function testOctalBaseMethod()
    {
        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($this->binaryFile)
            ->offset(0x10)
            ->read(Integer::class)
            ->setBase(BinaryUtilities::BASE_OCTAL)
            ->returnBuffer();

        static::assertCount(1, $byteArray);
        static::assertEquals(['211256034606'], $byteArray);
    }

    public function testHexadecimalBaseMethod()
    {
        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($this->binaryFile)
            ->offset(0x10)
            ->read(Integer::class)
            ->setBase(BinaryUtilities::BASE_HEXADECIMAL)
            ->returnBuffer();

        static::assertCount(1, $byteArray);
        static::assertEquals(['ffeeddcc'], $byteArray);
    }

    public function testQuickReadMethod()
    {
        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($this->binaryFile)
            ->readAndReturnFromOffset(0x10, Integer::class);

        static::assertEquals('4293844428', $byteArray);
    }

    /**
     * @return string
     */
    protected function bootstrapWriteableFile(): string
    {
        $binaryFile = $this->binaryFile;
        $binaryFileCopy = vfsStream::newFile('data-copy.bin')->at($this->virtualFileSystem)->url();

        if (file_exists($binaryFileCopy)) {
            unlink($binaryFileCopy);
        }

        copy($binaryFile, $binaryFileCopy);

        return $binaryFileCopy;
    }
}
