<?php

namespace PBurggraf\BinaryUtilities\Test\DataType;

use org\bovigo\vfs\vfsStream;
use PBurggraf\BinaryUtilities\BinaryUtilities;
use PBurggraf\BinaryUtilities\DataType\Byte;
use PBurggraf\BinaryUtilities\Exception\EndOfFileReachedException;
use PBurggraf\BinaryUtilities\Test\BinaryUtilitiesTest;

/**
 * @author Philip Burggraf <philip@pburggraf.de>
 */
class ByteTest extends BinaryUtilitiesTest
{
    public function testReadFirstSingleByte()
    {
        $binaryUtility = new BinaryUtilities();
        $byteArray = $binaryUtility
            ->setFile($this->binaryFile)
            ->read(Byte::class)
            ->returnBuffer();

        static::assertCount(1, $byteArray);
        static::assertEquals([0], $byteArray);
    }

    public function testReadFirstThreeBytes()
    {
        $binaryUtility = new BinaryUtilities();
        $byteArray = $binaryUtility
            ->setFile($this->binaryFile)
            ->read(Byte::class)
            ->read(Byte::class)
            ->read(Byte::class)
            ->returnBuffer();

        static::assertCount(3, $byteArray);
        static::assertEquals([0, 17, 34], $byteArray);
    }

    public function testReadFirstThreeBytesWithArray()
    {
        $binaryUtility = new BinaryUtilities();
        $byteArray = $binaryUtility
            ->setFile($this->binaryFile)
            ->readArray(Byte::class, 3)
            ->returnBuffer();

        static::assertCount(3, $byteArray);
        static::assertEquals([0, 17, 34], $byteArray);
    }

    public function testWriteFirstSingleByte()
    {
        $binaryFileCopy = $this->bootstrapWriteableFile();

        $binaryUtility = new BinaryUtilities();
        $binaryUtility
            ->setFile($binaryFileCopy)
            ->write(Byte::class, 160)
            ->save();

        $binaryUtility = new BinaryUtilities();
        $byteArray = $binaryUtility
            ->setFile($binaryFileCopy)
            ->read(Byte::class)
            ->returnBuffer();

        static::assertCount(1, $byteArray);
        static::assertEquals([160], $byteArray);
    }

    public function testWriteFirstThreeBytes()
    {
        $binaryFileCopy = $this->bootstrapWriteableFile();

        $binaryUtility = new BinaryUtilities();
        $binaryUtility
            ->setFile($binaryFileCopy)
            ->write(Byte::class, 160)
            ->write(Byte::class, 161)
            ->write(Byte::class, 162)
            ->save();

        $binaryUtility = new BinaryUtilities();
        $byteArray = $binaryUtility
            ->setFile($binaryFileCopy)
            ->read(Byte::class)
            ->read(Byte::class)
            ->read(Byte::class)
            ->returnBuffer();

        static::assertCount(3, $byteArray);
        static::assertEquals([160, 161, 162], $byteArray);
    }

    public function testWriteFirstThreeBytesWithArray()
    {
        $binaryFileCopy = $this->bootstrapWriteableFile();

        $binaryUtility = new BinaryUtilities();
        $binaryUtility
            ->setFile($binaryFileCopy)
            ->writeArray(Byte::class, [160, 161, 162])
            ->save();

        $binaryUtility = new BinaryUtilities();
        $byteArray = $binaryUtility
            ->setFile($binaryFileCopy)
            ->readArray(Byte::class, 3)
            ->returnBuffer();

        static::assertCount(3, $byteArray);
        static::assertEquals([160, 161, 162], $byteArray);
    }

    public function testReadOverEndOfFile()
    {
        $this->expectException(EndOfFileReachedException::class);

        $binaryUtility = new BinaryUtilities();
        $binaryUtility
            ->setFile($this->binaryFile)
            ->offset(40)
            ->read(Byte::class)
            ->read(Byte::class);
    }

    public function testReadOverEndOfFileWithArray()
    {
        $this->expectException(EndOfFileReachedException::class);

        $binaryUtility = new BinaryUtilities();
        $binaryUtility
            ->setFile($this->binaryFile)
            ->offset(40)
            ->readArray(Byte::class, 2);
    }

    public function testWriteOverEndOfFile()
    {
        $this->expectException(EndOfFileReachedException::class);

        $binaryFileCopy = $this->bootstrapWriteableFile();

        $binaryUtility = new BinaryUtilities();
        $binaryUtility
            ->setFile($binaryFileCopy)
            ->offset(40)
            ->write(Byte::class, 160)
            ->write(Byte::class, 161)
            ->save();
    }

    public function testWriteOverEndOfFileWithArray()
    {
        $this->expectException(EndOfFileReachedException::class);

        $binaryFileCopy = $this->bootstrapWriteableFile();

        $binaryUtility = new BinaryUtilities();
        $binaryUtility
            ->setFile($binaryFileCopy)
            ->offset(40)
            ->writeArray(Byte::class, [160, 161])
            ->save();
    }
}
