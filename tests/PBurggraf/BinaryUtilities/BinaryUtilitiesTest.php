<?php

declare(strict_types=1);

namespace PBurggraf\BinaryUtilities\Test;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PBurggraf\BinaryUtilities\BinaryUtilities;
use PBurggraf\BinaryUtilities\BinaryUtilityFactory;
use PBurggraf\BinaryUtilities\DataType\Integer;
use PBurggraf\BinaryUtilities\Exception\DataTypeDoesNotExistsException;
use PBurggraf\BinaryUtilities\Exception\EndianTypeDoesNotExistsException;
use PBurggraf\BinaryUtilities\Exception\FileDoesNotExistsException;
use PBurggraf\BinaryUtilities\Exception\FileErrorException;
use PBurggraf\BinaryUtilities\Exception\FileNotAccessableException;
use PBurggraf\BinaryUtilities\Exception\InvalidDataTypeException;
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
    protected function setUp(): void
    {
        $this->virtualFileSystem = vfsStream::setup();
        $virtualFile = vfsStream::newFile('data.bin')
            ->at($this->virtualFileSystem)
            ->setContent((string) hex2bin('00112233445566778899aabbccddeeffffeeddccbbaa998877665544332211000102040810204080'));

        $this->binaryFile = $virtualFile->url();
    }

    /**
     * @throws FileDoesNotExistsException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     */
    public function testReadNonExistingFile(): void
    {
        $this->expectException(FileDoesNotExistsException::class);

        $binaryUtility = BinaryUtilityFactory::create();
        $binaryUtility->setFile('nonExistingFile.bin');
    }

    /**
     * @throws FileDoesNotExistsException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws InvalidDataTypeException
     */
    public function testBinaryBaseMethod(): void
    {
        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($this->binaryFile)
            ->setOffset(0x10)
            ->read(Integer::class)
            ->setBase(BinaryUtilities::BASE_BINARY)
            ->returnBuffer();

        static::assertCount(1, $byteArray);
        static::assertEquals(['11111111111011101101110111001100'], $byteArray);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     * @throws InvalidDataTypeException
     */
    public function testOctalBaseMethod(): void
    {
        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($this->binaryFile)
            ->setOffset(0x10)
            ->read(Integer::class)
            ->setBase(BinaryUtilities::BASE_OCTAL)
            ->returnBuffer();

        static::assertCount(1, $byteArray);
        static::assertEquals(['211256034606'], $byteArray);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     * @throws InvalidDataTypeException
     */
    public function testHexadecimalBaseMethod(): void
    {
        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($this->binaryFile)
            ->setOffset(0x10)
            ->read(Integer::class)
            ->setBase(BinaryUtilities::BASE_HEXADECIMAL)
            ->returnBuffer();

        static::assertCount(1, $byteArray);
        static::assertEquals(['ffeeddcc'], $byteArray);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     * @throws InvalidDataTypeException
     */
    public function testQuickReadMethod(): void
    {
        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($this->binaryFile)
            ->readAndReturnFromOffset(0x10, Integer::class);

        static::assertEquals('4293844428', $byteArray);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws InvalidDataTypeException
     */
    public function testSetContentMethod(): void
    {
        $binaryUtility = BinaryUtilityFactory::create();

        $byteArray = $binaryUtility
            ->setContent((string) hex2bin('00112233445566778899aabbccddeeffffeeddccbbaa998877665544332211000102040810204080'))
            ->setOffset(0x10)
            ->read(Integer::class)
            ->setBase(BinaryUtilities::BASE_BINARY)
            ->returnBuffer();

        static::assertCount(1, $byteArray);
        static::assertEquals(['11111111111011101101110111001100'], $byteArray);
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
