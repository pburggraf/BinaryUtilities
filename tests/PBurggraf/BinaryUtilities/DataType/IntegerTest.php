<?php

namespace PBurggraf\BinaryUtilities\Test\DataType;

use org\bovigo\vfs\vfsStream;
use PBurggraf\BinaryUtilities\BinaryUtilities;
use PBurggraf\BinaryUtilities\DataType\Integer;
use PBurggraf\BinaryUtilities\EndianType\BigEndian;
use PBurggraf\BinaryUtilities\EndianType\LittleEndian;
use PBurggraf\BinaryUtilities\Test\BinaryUtilitiesTest;

/**
 * @author Philip Burggraf <philip@pburggraf.de>
 */
class IntegerTest extends BinaryUtilitiesTest
{
    public function testReadFirstSingleIntegerBigEndian()
    {
        $binaryUtility = new BinaryUtilities();
        $int = $binaryUtility
            ->setFile($this->binaryFile)
            ->read(Integer::class)
            ->returnBuffer();

        static::assertCount(1, $int);
        static::assertEquals([1122867], $int);
    }

    public function testReadFirstThreeIntegerBigEndian()
    {
        $binaryUtility = new BinaryUtilities();
        $int = $binaryUtility
            ->setFile($this->binaryFile)
            ->read(Integer::class)
            ->read(Integer::class)
            ->read(Integer::class)
            ->returnBuffer();

        static::assertCount(3, $int);
        static::assertEquals([1122867, 1146447479, 2291772091], $int);
    }

    public function testReadFirstThreeIntegerWithArrayBigEndian()
    {
        $binaryUtility = new BinaryUtilities();
        $int = $binaryUtility
            ->setFile($this->binaryFile)
            ->readArray(Integer::class, 3)
            ->returnBuffer();

        static::assertCount(3, $int);
        static::assertEquals([1122867, 1146447479, 2291772091], $int);
    }

    public function testWriteFirstSingleIntegerBigEndian()
    {
        $binaryFile = $this->binaryFile;
        $binaryFileCopy = vfsStream::newFile('data-copy.bin')->at($this->virtualFileSystem)->url();

        copy($binaryFile, $binaryFileCopy);

        $binaryUtility = new BinaryUtilities();
        $binaryUtility->setFile($binaryFileCopy);

        $binaryUtility
            ->write(Integer::class, 0xa0b0c0d0)
            ->save();

        $binaryUtility = new BinaryUtilities();
        $binaryUtility->setFile($binaryFileCopy);

        $byteArray = $binaryUtility
            ->read(Integer::class)
            ->returnBuffer();

        static::assertCount(1, $byteArray);
        static::assertEquals([0xa0b0c0d0], $byteArray);

        unlink($binaryFileCopy);
    }

    public function testWriteFirstThreeIntegerBigEndian()
    {
        $binaryFile = $this->binaryFile;
        $binaryFileCopy = vfsStream::newFile('data-copy.bin')->at($this->virtualFileSystem)->url();

        copy($binaryFile, $binaryFileCopy);

        $binaryUtility = new BinaryUtilities();
        $binaryUtility->setFile($binaryFileCopy);

        $binaryUtility
            ->write(Integer::class, 0xa0b0c0d0)
            ->write(Integer::class, 0xa1b1c1d1)
            ->write(Integer::class, 0xa2b2c2d2)
            ->save();

        $binaryUtility = new BinaryUtilities();
        $binaryUtility->setFile($binaryFileCopy);

        $byteArray = $binaryUtility
            ->read(Integer::class)
            ->read(Integer::class)
            ->read(Integer::class)
            ->returnBuffer();

        static::assertCount(3, $byteArray);
        static::assertEquals([0xa0b0c0d0, 0xa1b1c1d1, 0xa2b2c2d2], $byteArray);

        unlink($binaryFileCopy);
    }

    public function testReadFirstSingleIntegerLittleEndian()
    {
        $binaryUtility = new BinaryUtilities();
        $int = $binaryUtility
            ->setFile($this->binaryFile)
            ->setEndian(LittleEndian::class)
            ->read(Integer::class)
            ->returnBuffer();

        static::assertCount(1, $int);
        static::assertEquals([857870592], $int);
    }

    public function testReadFirstThreeIntegerLittleEndian()
    {
        $binaryUtility = new BinaryUtilities();
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

    public function testReadFirstThreeIntegerWithArrayLittleEndian()
    {
        $binaryUtility = new BinaryUtilities();
        $int = $binaryUtility
            ->setFile($this->binaryFile)
            ->setEndian(LittleEndian::class)
            ->readArray(Integer::class, 3)
            ->returnBuffer();

        static::assertCount(3, $int);
        static::assertEquals([857870592, 2003195204, 3148519816], $int);
    }

    public function testWriteFirstSingleIntegerLittleEndian()
    {
        $binaryFile = $this->binaryFile;
        $binaryFileCopy = vfsStream::newFile('data-copy.bin')->at($this->virtualFileSystem)->url();

        copy($binaryFile, $binaryFileCopy);

        $binaryUtility = new BinaryUtilities();
        $binaryUtility->setFile($binaryFileCopy);

        $binaryUtility
            ->setEndian(LittleEndian::class)
            ->write(Integer::class, 0xa0b0c0d0)
            ->save();

        $binaryUtility = new BinaryUtilities();
        $binaryUtility->setFile($binaryFileCopy);

        $byteArray = $binaryUtility
            ->setEndian(LittleEndian::class)
            ->read(Integer::class)
            ->returnBuffer();

        static::assertCount(1, $byteArray);
        static::assertEquals([0xa0b0c0d0], $byteArray);

        $binaryUtility = new BinaryUtilities();
        $binaryUtility->setFile($binaryFileCopy);

        $byteArray = $binaryUtility
            ->setEndian(BigEndian::class)
            ->read(Integer::class)
            ->returnBuffer();

        static::assertCount(1, $byteArray);
        static::assertEquals([0xd0c0b0a0], $byteArray);

        unlink($binaryFileCopy);
    }

    public function testWriteFirstThreeIntegerLittleEndian()
    {
        $binaryFile = $this->binaryFile;
        $binaryFileCopy = vfsStream::newFile('data-copy.bin')->at($this->virtualFileSystem)->url();

        copy($binaryFile, $binaryFileCopy);

        $binaryUtility = new BinaryUtilities();
        $binaryUtility->setFile($binaryFileCopy);

        $binaryUtility
            ->setEndian(LittleEndian::class)
            ->write(Integer::class, 0xa0b0c0d0)
            ->write(Integer::class, 0xa1b1c1d1)
            ->write(Integer::class, 0xa2b2c2d2)
            ->save();

        $binaryUtility = new BinaryUtilities();
        $binaryUtility->setFile($binaryFileCopy);

        $byteArray = $binaryUtility
            ->setEndian(LittleEndian::class)
            ->read(Integer::class)
            ->read(Integer::class)
            ->read(Integer::class)
            ->returnBuffer();

        static::assertCount(3, $byteArray);
        static::assertEquals([0xa0b0c0d0, 0xa1b1c1d1, 0xa2b2c2d2], $byteArray);

        $binaryUtility = new BinaryUtilities();
        $binaryUtility->setFile($binaryFileCopy);

        $byteArray = $binaryUtility
            ->setEndian(BigEndian::class)
            ->read(Integer::class)
            ->read(Integer::class)
            ->read(Integer::class)
            ->returnBuffer();

        static::assertCount(3, $byteArray);
        static::assertEquals([0xd0c0b0a0, 0xd1c1b1a1, 0xd2c2b2a2], $byteArray);

        unlink($binaryFileCopy);
    }
}
