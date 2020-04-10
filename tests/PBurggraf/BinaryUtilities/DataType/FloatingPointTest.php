<?php

declare(strict_types=1);

namespace PBurggraf\BinaryUtilities\Test\DataType;

use PBurggraf\BinaryUtilities\BinaryUtilityFactory;
use PBurggraf\BinaryUtilities\DataType\FloatingPoint;
use PBurggraf\BinaryUtilities\EndianType\BigEndian;
use PBurggraf\BinaryUtilities\EndianType\LittleEndian;
use PBurggraf\BinaryUtilities\Exception\ContentOnlyException;
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
class FloatingPointTest extends BinaryUtilitiesTest
{
    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws InvalidDataTypeException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     */
    public function testReadFirstSingleFloatingPointBigEndian(): void
    {
        $binaryUtility = BinaryUtilityFactory::create();
        $float = $binaryUtility
            ->setFile($this->binaryFile)
            ->read(FloatingPoint::class)
            ->returnBuffer(true, false);

        static::assertCount(1, $float);
        static::assertEquals([1.5734718027410144E-39], $float);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     * @throws InvalidDataTypeException
     */
    public function testReadFirstThreeFloatingPointBigEndian(): void
    {
        $binaryUtility = BinaryUtilityFactory::create();
        $int = $binaryUtility
            ->setFile($this->binaryFile)
            ->read(FloatingPoint::class)
            ->read(FloatingPoint::class)
            ->read(FloatingPoint::class)
            ->returnBuffer(true, false);

        static::assertCount(3, $int);
        static::assertEquals([1.5734718027410144E-39, 853.6010131835938, -9.248491086907245E-34], $int);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     * @throws InvalidDataTypeException
     */
    public function testReadFirstThreeFloatingPointWithArrayBigEndian(): void
    {
        $binaryUtility = BinaryUtilityFactory::create();
        $int = $binaryUtility
            ->setFile($this->binaryFile)
            ->readArray(FloatingPoint::class, 3)
            ->returnBuffer(true, false);

        static::assertCount(3, $int);
        static::assertEquals([1.5734718027410144E-39, 853.6010131835938, -9.248491086907245E-34], $int);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     * @throws InvalidDataTypeException
     * @throws ContentOnlyException
     */
    public function testWriteFirstSingleFloatingPointBigEndian(): void
    {
        $binaryFileCopy = $this->bootstrapWriteableFile();

        $binaryUtility = BinaryUtilityFactory::create();
        $binaryUtility->setFile($binaryFileCopy);

        $binaryUtility
            ->write(FloatingPoint::class, 1234.5678)
            ->save();

        $binaryUtility = BinaryUtilityFactory::create();
        $binaryUtility->setFile($binaryFileCopy);

        $byteArray = $binaryUtility
            ->read(FloatingPoint::class)
            ->returnBuffer(true, false);

        static::assertCount(1, $byteArray);
        static::assertEquals([1234.5677490234375], $byteArray);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     * @throws InvalidDataTypeException
     * @throws ContentOnlyException
     */
    public function testWriteFirstThreeFloatingPointBigEndian(): void
    {
        $binaryFileCopy = $this->bootstrapWriteableFile();

        $binaryUtility = BinaryUtilityFactory::create();
        $binaryUtility
            ->setFile($binaryFileCopy)
            ->write(FloatingPoint::class, 1234.5678)
            ->write(FloatingPoint::class, 13.37)
            ->write(FloatingPoint::class, 100)
            ->save();

        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($binaryFileCopy)
            ->read(FloatingPoint::class)
            ->read(FloatingPoint::class)
            ->read(FloatingPoint::class)
            ->returnBuffer(true, false);

        static::assertCount(3, $byteArray);
        static::assertEquals([1234.5677490234375, 13.369999885559082, 100.0], $byteArray);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     * @throws InvalidDataTypeException
     * @throws ContentOnlyException
     */
    public function testWriteFirstThreeFloatingPointWithArrayBigEndian(): void
    {
        $binaryFileCopy = $this->bootstrapWriteableFile();

        $binaryUtility = BinaryUtilityFactory::create();
        $binaryUtility
            ->setFile($binaryFileCopy)
            ->writeArray(FloatingPoint::class, [1234.5678, 13.37, 100])
            ->save();

        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($binaryFileCopy)
            ->readArray(FloatingPoint::class, 3)
            ->returnBuffer(true, false);

        static::assertCount(3, $byteArray);
        static::assertEquals([1234.5677490234375, 13.369999885559082, 100.0], $byteArray);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     * @throws InvalidDataTypeException
     */
    public function testReadFirstSingleFloatingPointLittleEndian(): void
    {
        $binaryUtility = BinaryUtilityFactory::create();
        $int = $binaryUtility
            ->setFile($this->binaryFile)
            ->setEndian(LittleEndian::class)
            ->read(FloatingPoint::class)
            ->returnBuffer(true, false);

        static::assertCount(1, $int);
        static::assertEquals([3.773402568185702E-8], $int);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     * @throws InvalidDataTypeException
     */
    public function testReadFirstThreeFloatingPointLittleEndian(): void
    {
        $binaryUtility = BinaryUtilityFactory::create();
        $int = $binaryUtility
            ->setFile($this->binaryFile)
            ->setEndian(LittleEndian::class)
            ->read(FloatingPoint::class)
            ->read(FloatingPoint::class)
            ->read(FloatingPoint::class)
            ->returnBuffer(true, false);

        static::assertCount(3, $int);
        static::assertEquals([3.773402568185702E-8, 4.6717096476342645E+33, -0.005206290632486343], $int);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     * @throws InvalidDataTypeException
     */
    public function testReadFirstThreeFloatingPointWithArrayLittleEndian(): void
    {
        $binaryUtility = BinaryUtilityFactory::create();
        $int = $binaryUtility
            ->setFile($this->binaryFile)
            ->setEndian(LittleEndian::class)
            ->readArray(FloatingPoint::class, 3)
            ->returnBuffer(true, false);

        static::assertCount(3, $int);
        static::assertEquals([3.773402568185702E-8, 4.6717096476342645E+33, -0.005206290632486343], $int);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     * @throws InvalidDataTypeException
     * @throws ContentOnlyException
     */
    public function testWriteFirstSingleFloatingPointLittleEndian(): void
    {
        $binaryFileCopy = $this->bootstrapWriteableFile();

        $binaryUtility = BinaryUtilityFactory::create();
        $binaryUtility
            ->setFile($binaryFileCopy)
            ->setEndian(LittleEndian::class)
            ->write(FloatingPoint::class, 1234.5678)
            ->save();

        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($binaryFileCopy)
            ->setEndian(LittleEndian::class)
            ->read(FloatingPoint::class)
            ->returnBuffer(true, false);

        static::assertCount(1, $byteArray);
        static::assertEquals([1234.5677490234375], $byteArray);

        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($binaryFileCopy)
            ->setEndian(BigEndian::class)
            ->read(FloatingPoint::class)
            ->returnBuffer(true, false);

        static::assertCount(1, $byteArray);
        static::assertEquals([7.482107381578951E-13], $byteArray);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     * @throws InvalidDataTypeException
     * @throws ContentOnlyException
     */
    public function testWriteFirstThreeFloatingPointLittleEndian(): void
    {
        $binaryFileCopy = $this->bootstrapWriteableFile();

        $binaryUtility = BinaryUtilityFactory::create();
        $binaryUtility
            ->setFile($binaryFileCopy)
            ->setEndian(LittleEndian::class)
            ->write(FloatingPoint::class, 1234.5678)
            ->write(FloatingPoint::class, 13.37)
            ->write(FloatingPoint::class, 100)
            ->save();

        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($binaryFileCopy)
            ->setEndian(LittleEndian::class)
            ->read(FloatingPoint::class)
            ->read(FloatingPoint::class)
            ->read(FloatingPoint::class)
            ->returnBuffer(true, false);

        static::assertCount(3, $byteArray);
        static::assertEquals([1234.5677490234375, 13.369999885559082, 100.0], $byteArray);

        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($binaryFileCopy)
            ->setEndian(BigEndian::class)
            ->read(FloatingPoint::class)
            ->read(FloatingPoint::class)
            ->read(FloatingPoint::class)
            ->returnBuffer(true, false);

        static::assertCount(3, $byteArray);
        static::assertEquals([7.482107381578951E-13, -2.2130611134578508E-35, 7.183896707207607E-41], $byteArray);
    }

    /**
     * @throws DataTypeDoesNotExistsException
     * @throws EndianTypeDoesNotExistsException
     * @throws FileDoesNotExistsException
     * @throws FileErrorException
     * @throws FileNotAccessableException
     * @throws InvalidDataTypeException
     * @throws ContentOnlyException
     */
    public function testWriteFirstThreeFloatingPointWithArrayLittleEndian(): void
    {
        $binaryFileCopy = $this->bootstrapWriteableFile();

        $binaryUtility = BinaryUtilityFactory::create();
        $binaryUtility
            ->setFile($binaryFileCopy)
            ->setEndian(LittleEndian::class)
            ->writeArray(FloatingPoint::class, [1234.5678, 13.37, 100])
            ->save();

        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($binaryFileCopy)
            ->setEndian(LittleEndian::class)
            ->readArray(FloatingPoint::class, 3)
            ->returnBuffer(true, false);

        static::assertCount(3, $byteArray);
        static::assertEquals([1234.5677490234375, 13.369999885559082, 100.0], $byteArray);

        $binaryUtility = BinaryUtilityFactory::create();
        $byteArray = $binaryUtility
            ->setFile($binaryFileCopy)
            ->setEndian(BigEndian::class)
            ->readArray(FloatingPoint::class, 3)
            ->returnBuffer(true, false);

        static::assertCount(3, $byteArray);
        static::assertEquals([7.482107381578951E-13, -2.2130611134578508E-35, 7.183896707207607E-41], $byteArray);
    }
}
