<?php

namespace PBurggraf\BinaryUtilities\Test\DataType;

use PBurggraf\BinaryUtilities\BinaryUtilities;
use PBurggraf\BinaryUtilities\BinaryUtilityFactory;
use PBurggraf\BinaryUtilities\DataType\Byte;
use PBurggraf\BinaryUtilities\Exception\DataTypeDoesNotExistsException;
use PBurggraf\BinaryUtilities\Exception\EndianTypeDoesNotExistsException;
use PBurggraf\BinaryUtilities\Exception\EndOfFileReachedException;
use PBurggraf\BinaryUtilities\Exception\FileDoesNotExistsException;
use PBurggraf\BinaryUtilities\Exception\InvalidDataTypeException;
use PBurggraf\BinaryUtilities\Test\BinaryUtilitiesTest;

/**
 * @author Philip Burggraf <philip@pburggraf.de>
 */
class ByteTest extends BinaryUtilitiesTest
{
    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws InvalidDataTypeException
     */
    public function testReadFirstSingleByte()
    {
        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($this->binaryFile)
            ->read(Byte::class)
            ->returnBuffer();

        static::assertCount(1, $byteArray);
        static::assertEquals([0], $byteArray);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws InvalidDataTypeException
     */
    public function testReadFirstThreeBytes()
    {
        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($this->binaryFile)
            ->read(Byte::class)
            ->read(Byte::class)
            ->read(Byte::class)
            ->returnBuffer();

        static::assertCount(3, $byteArray);
        static::assertEquals([0, 17, 34], $byteArray);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws InvalidDataTypeException
     */
    public function testReadFirstThreeBytesWithArray()
    {
        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($this->binaryFile)
            ->readArray(Byte::class, 3)
            ->returnBuffer();

        static::assertCount(3, $byteArray);
        static::assertEquals([0, 17, 34], $byteArray);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws InvalidDataTypeException
     */
    public function testWriteFirstSingleByte()
    {
        $binaryFileCopy = $this->bootstrapWriteableFile();

        $binaryUtility = BinaryUtilityFactory::create();
        $binaryUtility
            ->setFile($binaryFileCopy)
            ->write(Byte::class, 160)
            ->save();

        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($binaryFileCopy)
            ->read(Byte::class)
            ->returnBuffer();

        static::assertCount(1, $byteArray);
        static::assertEquals([160], $byteArray);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws InvalidDataTypeException
     */
    public function testWriteFirstThreeBytes()
    {
        $binaryFileCopy = $this->bootstrapWriteableFile();

        $binaryUtility = BinaryUtilityFactory::create();
        $binaryUtility
            ->setFile($binaryFileCopy)
            ->write(Byte::class, 160)
            ->write(Byte::class, 161)
            ->write(Byte::class, 162)
            ->save();

        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($binaryFileCopy)
            ->read(Byte::class)
            ->read(Byte::class)
            ->read(Byte::class)
            ->returnBuffer();

        static::assertCount(3, $byteArray);
        static::assertEquals([160, 161, 162], $byteArray);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws InvalidDataTypeException
     */
    public function testWriteFirstThreeBytesWithArray()
    {
        $binaryFileCopy = $this->bootstrapWriteableFile();

        $binaryUtility = BinaryUtilityFactory::create();
        $binaryUtility
            ->setFile($binaryFileCopy)
            ->writeArray(Byte::class, [160, 161, 162])
            ->save();

        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($binaryFileCopy)
            ->readArray(Byte::class, 3)
            ->returnBuffer();

        static::assertCount(3, $byteArray);
        static::assertEquals([160, 161, 162], $byteArray);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndOfFileReachedException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws InvalidDataTypeException
     */
    public function testReadOverEndOfFile()
    {
        $this->expectException(EndOfFileReachedException::class);

        $binaryUtility = BinaryUtilityFactory::create();
        $binaryUtility
            ->setFile($this->binaryFile)
            ->offset(40)
            ->read(Byte::class)
            ->read(Byte::class);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndOfFileReachedException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws InvalidDataTypeException
     */
    public function testReadOverEndOfFileWithArray()
    {
        $this->expectException(EndOfFileReachedException::class);

        $binaryUtility = BinaryUtilityFactory::create();
        $binaryUtility
            ->setFile($this->binaryFile)
            ->offset(40)
            ->readArray(Byte::class, 2);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndOfFileReachedException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws InvalidDataTypeException
     */
    public function testWriteOverEndOfFile()
    {
        $this->expectException(EndOfFileReachedException::class);

        $binaryFileCopy = $this->bootstrapWriteableFile();

        $binaryUtility = BinaryUtilityFactory::create();
        $binaryUtility
            ->setFile($binaryFileCopy)
            ->offset(40)
            ->write(Byte::class, 160)
            ->write(Byte::class, 161)
            ->save();
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndOfFileReachedException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws InvalidDataTypeException
     */
    public function testWriteOverEndOfFileWithArray()
    {
        $this->expectException(EndOfFileReachedException::class);

        $binaryFileCopy = $this->bootstrapWriteableFile();

        $binaryUtility = BinaryUtilityFactory::create();
        $binaryUtility
            ->setFile($binaryFileCopy)
            ->offset(40)
            ->writeArray(Byte::class, [160, 161])
            ->save();
    }
}
