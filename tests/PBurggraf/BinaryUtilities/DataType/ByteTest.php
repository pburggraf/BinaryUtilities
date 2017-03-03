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
        $binaryUtility->setFile($this->binaryFile);

        $byteArray = $binaryUtility
            ->read(Byte::class)
            ->returnBuffer();

        static::assertCount(1, $byteArray);
        static::assertEquals([0], $byteArray);
    }

    public function testReadFirstThreeBytes()
    {
        $binaryUtility = new BinaryUtilities();
        $binaryUtility->setFile($this->binaryFile);

        $byteArray = $binaryUtility
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
        $binaryUtility->setFile($this->binaryFile);

        $byteArray = $binaryUtility
            ->readArray(Byte::class, 3)
            ->returnBuffer();

        static::assertCount(3, $byteArray);
        static::assertEquals([0, 17, 34], $byteArray);
    }

    public function testReadOverEndOfFile()
    {
        $this->expectException(EndOfFileReachedException::class);

        $binaryUtility = new BinaryUtilities();
        $binaryUtility->setFile($this->binaryFile);

        $binaryUtility->offset(40)->read(Byte::class)->read(Byte::class);
    }

    public function testReadOverEndOfFileWithArray()
    {
        $this->expectException(EndOfFileReachedException::class);

        $binaryUtility = new BinaryUtilities();
        $binaryUtility->setFile($this->binaryFile);

        $binaryUtility->offset(40)->readArray(Byte::class, 2);
    }

    public function testWriteFirstSingleByte()
    {
        $binaryFile = $this->binaryFile;
        $binaryFileCopy = vfsStream::newFile('data-copy.bin')->at($this->virtualFileSystem)->url();

        copy($binaryFile, $binaryFileCopy);

        $binaryUtility = new BinaryUtilities();
        $binaryUtility->setFile($binaryFileCopy);

        $binaryUtility
            ->write(Byte::class, 0xa0)
            ->save();

        $binaryUtility = new BinaryUtilities();
        $binaryUtility->setFile($binaryFileCopy);

        $byteArray = $binaryUtility
            ->read(Byte::class)
            ->returnBuffer();

        static::assertCount(1, $byteArray);
        static::assertEquals([0xa0], $byteArray);

        unlink($binaryFileCopy);
    }

    public function testWriteFirstThreeBytes()
    {
        $binaryFile = $this->binaryFile;
        $binaryFileCopy = vfsStream::newFile('data-copy.bin')->at($this->virtualFileSystem)->url();

        copy($binaryFile, $binaryFileCopy);

        $binaryUtility = new BinaryUtilities();
        $binaryUtility->setFile($binaryFileCopy);

        $binaryUtility
            ->write(Byte::class, 0xa0)
            ->write(Byte::class, 0xa1)
            ->write(Byte::class, 0xa2)
            ->save();

        $binaryUtility = new BinaryUtilities();
        $binaryUtility->setFile($binaryFileCopy);

        $byteArray = $binaryUtility
            ->read(Byte::class)
            ->read(Byte::class)
            ->read(Byte::class)
            ->returnBuffer();

        static::assertCount(3, $byteArray);
        static::assertEquals([0xa0, 0xa1, 0xa2], $byteArray);

        unlink($binaryFileCopy);
    }
}
