<?php

declare(strict_types=1);

namespace PBurggraf\BinaryUtilities\Test\DataType;

use PBurggraf\BinaryUtilities\BinaryUtilities;
use PBurggraf\BinaryUtilities\BinaryUtilityFactory;
use PBurggraf\BinaryUtilities\DataType\Integer;
use PBurggraf\BinaryUtilities\EndianType\BigEndian;
use PBurggraf\BinaryUtilities\EndianType\LittleEndian;
use PBurggraf\BinaryUtilities\Exception\DataTypeDoesNotExistsException;
use PBurggraf\BinaryUtilities\Exception\EndianTypeDoesNotExistsException;
use PBurggraf\BinaryUtilities\Exception\FileDoesNotExistsException;
use PBurggraf\BinaryUtilities\Exception\FileErrorException;
use PBurggraf\BinaryUtilities\Exception\FileNotAccessableException;
use PBurggraf\BinaryUtilities\Exception\InvalidDataTypeException;
use PBurggraf\BinaryUtilities\Test\BinaryUtilitiesTest;

/**
 * @author Philip Burggraf <philip@pburggraf.de>
 */
class IntegerTest extends BinaryUtilitiesTest
{
    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws InvalidDataTypeException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     */
    public function testReadFirstSingleIntegerBigEndian()
    {
        $binaryUtility = BinaryUtilityFactory::create();
        $int = $binaryUtility
            ->setFile($this->binaryFile)
            ->read(Integer::class)
            ->returnBuffer();

        static::assertCount(1, $int);
        static::assertEquals([1122867], $int);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     * @throws InvalidDataTypeException
     */
    public function testReadFirstThreeIntegerBigEndian()
    {
        $binaryUtility = BinaryUtilityFactory::create();
        $int = $binaryUtility
            ->setFile($this->binaryFile)
            ->read(Integer::class)
            ->read(Integer::class)
            ->read(Integer::class)
            ->returnBuffer();

        static::assertCount(3, $int);
        static::assertEquals([1122867, 1146447479, 2291772091], $int);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     * @throws InvalidDataTypeException
     */
    public function testReadFirstThreeIntegerWithArrayBigEndian()
    {
        $binaryUtility = BinaryUtilityFactory::create();
        $int = $binaryUtility
            ->setFile($this->binaryFile)
            ->readArray(Integer::class, 3)
            ->returnBuffer();

        static::assertCount(3, $int);
        static::assertEquals([1122867, 1146447479, 2291772091], $int);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     * @throws InvalidDataTypeException
     */
    public function testWriteFirstSingleIntegerBigEndian()
    {
        $binaryFileCopy = $this->bootstrapWriteableFile();

        $binaryUtility = BinaryUtilityFactory::create();
        $binaryUtility->setFile($binaryFileCopy);

        $binaryUtility
            ->write(Integer::class, 0xa0b0c0d0)
            ->save();

        $binaryUtility = BinaryUtilityFactory::create();
        $binaryUtility->setFile($binaryFileCopy);

        $byteArray = $binaryUtility
            ->read(Integer::class)
            ->returnBuffer();

        static::assertCount(1, $byteArray);
        static::assertEquals([0xa0b0c0d0], $byteArray);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     * @throws InvalidDataTypeException
     */
    public function testWriteFirstThreeIntegerBigEndian()
    {
        $binaryFileCopy = $this->bootstrapWriteableFile();

        $binaryUtility = BinaryUtilityFactory::create();
        $binaryUtility
            ->setFile($binaryFileCopy)
            ->write(Integer::class, 0xa0b0c0d0)
            ->write(Integer::class, 0xa1b1c1d1)
            ->write(Integer::class, 0xa2b2c2d2)
            ->save();

        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($binaryFileCopy)
            ->read(Integer::class)
            ->read(Integer::class)
            ->read(Integer::class)
            ->returnBuffer();

        static::assertCount(3, $byteArray);
        static::assertEquals([0xa0b0c0d0, 0xa1b1c1d1, 0xa2b2c2d2], $byteArray);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     * @throws InvalidDataTypeException
     */
    public function testWriteFirstThreeIntegerWithArrayBigInteger()
    {
        $binaryFileCopy = $this->bootstrapWriteableFile();

        $binaryUtility = BinaryUtilityFactory::create();
        $binaryUtility
            ->setFile($binaryFileCopy)
            ->writeArray(Integer::class, [0xa0b0c0d0, 0xa1b1c1d1, 0xa2b2c2d2])
            ->save();

        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($binaryFileCopy)
            ->readArray(Integer::class, 3)
            ->returnBuffer();

        static::assertCount(3, $byteArray);
        static::assertEquals([0xa0b0c0d0, 0xa1b1c1d1, 0xa2b2c2d2], $byteArray);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     * @throws InvalidDataTypeException
     */
    public function testReadFirstSingleIntegerLittleEndian()
    {
        $binaryUtility = BinaryUtilityFactory::create();
        $int = $binaryUtility
            ->setFile($this->binaryFile)
            ->setEndian(LittleEndian::class)
            ->read(Integer::class)
            ->returnBuffer();

        static::assertCount(1, $int);
        static::assertEquals([857870592], $int);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     * @throws InvalidDataTypeException
     */
    public function testReadFirstThreeIntegerLittleEndian()
    {
        $binaryUtility = BinaryUtilityFactory::create();
        $int = $binaryUtility
            ->setFile($this->binaryFile)
            ->setEndian(LittleEndian::class)
            ->read(Integer::class)
            ->read(Integer::class)
            ->read(Integer::class)
            ->returnBuffer();

        static::assertCount(3, $int);
        static::assertEquals([857870592, 2003195204, 3148519816], $int);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     * @throws InvalidDataTypeException
     */
    public function testReadFirstThreeIntegerWithArrayLittleEndian()
    {
        $binaryUtility = BinaryUtilityFactory::create();
        $int = $binaryUtility
            ->setFile($this->binaryFile)
            ->setEndian(LittleEndian::class)
            ->readArray(Integer::class, 3)
            ->returnBuffer();

        static::assertCount(3, $int);
        static::assertEquals([857870592, 2003195204, 3148519816], $int);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     * @throws InvalidDataTypeException
     */
    public function testWriteFirstSingleIntegerLittleEndian()
    {
        $binaryFileCopy = $this->bootstrapWriteableFile();

        $binaryUtility = BinaryUtilityFactory::create();
        $binaryUtility
            ->setFile($binaryFileCopy)
            ->setEndian(LittleEndian::class)
            ->write(Integer::class, 0xa0b0c0d0)
            ->save();

        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($binaryFileCopy)
            ->setEndian(LittleEndian::class)
            ->read(Integer::class)
            ->returnBuffer();

        static::assertCount(1, $byteArray);
        static::assertEquals([0xa0b0c0d0], $byteArray);

        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($binaryFileCopy)
            ->setEndian(BigEndian::class)
            ->read(Integer::class)
            ->returnBuffer();

        static::assertCount(1, $byteArray);
        static::assertEquals([0xd0c0b0a0], $byteArray);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     * @throws InvalidDataTypeException
     */
    public function testWriteFirstThreeIntegerLittleEndian()
    {
        $binaryFileCopy = $this->bootstrapWriteableFile();

        $binaryUtility = BinaryUtilityFactory::create();
        $binaryUtility
            ->setFile($binaryFileCopy)
            ->setEndian(LittleEndian::class)
            ->write(Integer::class, 0xa0b0c0d0)
            ->write(Integer::class, 0xa1b1c1d1)
            ->write(Integer::class, 0xa2b2c2d2)
            ->save();

        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($binaryFileCopy)
            ->setEndian(LittleEndian::class)
            ->read(Integer::class)
            ->read(Integer::class)
            ->read(Integer::class)
            ->returnBuffer();

        static::assertCount(3, $byteArray);
        static::assertEquals([0xa0b0c0d0, 0xa1b1c1d1, 0xa2b2c2d2], $byteArray);

        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($binaryFileCopy)
            ->setEndian(BigEndian::class)
            ->read(Integer::class)
            ->read(Integer::class)
            ->read(Integer::class)
            ->returnBuffer();

        static::assertCount(3, $byteArray);
        static::assertEquals([0xd0c0b0a0, 0xd1c1b1a1, 0xd2c2b2a2], $byteArray);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     * @throws InvalidDataTypeException
     */
    public function testWriteFirstThreeIntegerWithArrayLittleInteger()
    {
        $binaryFileCopy = $this->bootstrapWriteableFile();

        $binaryUtility = BinaryUtilityFactory::create();
        $binaryUtility
            ->setFile($binaryFileCopy)
            ->setEndian(LittleEndian::class)
            ->writeArray(Integer::class, [0xa0b0c0d0, 0xa1b1c1d1, 0xa2b2c2d2])
            ->save();

        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($binaryFileCopy)
            ->setEndian(LittleEndian::class)
            ->readArray(Integer::class, 3)
            ->returnBuffer();

        static::assertCount(3, $byteArray);
        static::assertEquals([0xa0b0c0d0, 0xa1b1c1d1, 0xa2b2c2d2], $byteArray);

        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($binaryFileCopy)
            ->setEndian(BigEndian::class)
            ->readArray(Integer::class, 3)
            ->returnBuffer();

        static::assertCount(3, $byteArray);
        static::assertEquals([0xd0c0b0a0, 0xd1c1b1a1, 0xd2c2b2a2], $byteArray);
    }
}
