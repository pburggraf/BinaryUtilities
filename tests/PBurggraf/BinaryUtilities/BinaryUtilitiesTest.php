<?php

namespace PBurggraf\BinaryUtilities\Test;

use PBurggraf\BinaryUtilities\BinaryUtilities;
use PBurggraf\BinaryUtilities\Exception\EndOfFileReachedException;
use PBurggraf\BinaryUtilities\Exception\FileDoesNotExistsException;
use PHPUnit\Framework\TestCase;

/**
 * @author Philip Burggraf <philip@pburggraf.de>
 */
class BinaryUtilitiesTest extends TestCase
{
    /**
     * @var
     */
    protected $binaryFile;

    /**
     * @var
     */
    protected $resourceFile;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->binaryFile = __DIR__ . '/../../Resources/data.bin';
    }

    public function testReadNonExistingFile()
    {
        $this->expectException(FileDoesNotExistsException::class);

        $binaryUtility = new BinaryUtilities();
        $binaryUtility->setFile('nonExistingFile.bin');
    }

    public function testReadFirstSingleByte()
    {
        //        $binaryFile = __DIR__ . '/../../Resources/data.bin';
//        $binaryFileCopy = '/tmp/' . md5(microtime());
//
//        copy($binaryFile, $binaryFileCopy);
//
//        $binaryUtility = new BinaryUtilities();
//        $binaryUtility->setFile($binaryFileCopy);
//
//        $firstByte = $binaryUtility->readByte();
//
//        static::assertEquals(0, $firstByte);
//
//        unlink($binaryFileCopy);
    }

    public function testReadFirstThreeBytes()
    {
        $binaryUtility = new BinaryUtilities();
        $binaryUtility->setFile($this->binaryFile);

        $byteArray = $binaryUtility->readByte()->readByte()->readByte()->returnBuffer();

        static::assertCount(3, $byteArray);
        static::assertEquals([0, 17, 34], $byteArray);
    }

    public function testReadFirstThreeBytesWithByteArray()
    {
        $binaryUtility = new BinaryUtilities();
        $binaryUtility->setFile($this->binaryFile);

        $byteArray = $binaryUtility->readByteArray(3)->returnBuffer();

        static::assertCount(3, $byteArray);
        static::assertEquals([0, 17, 34], $byteArray);
    }

    public function testReadWholeFile()
    {
        $binaryUtility = new BinaryUtilities();
        $binaryUtility->setFile($this->binaryFile);

        $byteArray = $binaryUtility->readByteArray($binaryUtility->endOfFile())->returnBuffer();

        static::assertCount(40, $byteArray);
        static::assertEquals([
            0, 17, 34, 51, 68, 85, 102, 119, 136, 153, 170, 187, 204, 221, 238, 255,
            255, 238, 221, 204, 187, 170, 153, 136, 119, 102, 85, 68, 51, 34, 17, 0,
            1, 2, 4, 8, 16, 32, 64, 128,
        ], $byteArray);
    }

    public function testReadOverEndOfFile()
    {
        $this->expectException(EndOfFileReachedException::class);

        $binaryUtility = new BinaryUtilities();
        $binaryUtility->setFile($this->binaryFile);

        $binaryUtility->offset(40)->readByte()->readByte();
    }

    public function testReadOverEndOfFileWithByteArray()
    {
        $this->expectException(EndOfFileReachedException::class);

        $binaryUtility = new BinaryUtilities();
        $binaryUtility->setFile($this->binaryFile);

        $binaryUtility->offset(40)->readByteArray(2);
    }

    public function testReadShortBigEndian()
    {
        $binaryUtility = new BinaryUtilities();
        $binaryUtility->setFile($this->binaryFile);

        $short = $binaryUtility->readShort()->returnBuffer();

        static::assertCount(1, $short);
        static::assertEquals([17], $short);
    }

    public function testReadShortLittleEndian()
    {
        $binaryUtility = new BinaryUtilities();
        $binaryUtility
            ->setFile($this->binaryFile)
            ->endian(BinaryUtilities::ENDIAN_LITTLE)
        ;

        $short = $binaryUtility->readShort()->returnBuffer();

        static::assertCount(1, $short);
        static::assertEquals([4352], $short);
    }

    public function testReadIntBigEndian()
    {
        $binaryUtility = new BinaryUtilities();
        $binaryUtility->setFile($this->binaryFile);

        $short = $binaryUtility->readInt()->returnBuffer();

        static::assertCount(1, $short);
        static::assertEquals([1122867], $short);
    }

    public function testReadIntLittleEndian()
    {
        $binaryUtility = new BinaryUtilities();
        $binaryUtility
            ->setFile($this->binaryFile)
            ->endian(BinaryUtilities::ENDIAN_LITTLE)
        ;

        $short = $binaryUtility->readInt()->returnBuffer();

        static::assertCount(1, $short);
        static::assertEquals([857870592], $short);
    }
}
