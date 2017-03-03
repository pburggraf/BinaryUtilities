<?php

namespace PBurggraf\BinaryUtilities\Test;

use PBurggraf\BinaryUtilities\BinaryUtilities;
use PBurggraf\BinaryUtilities\DataType\Byte;
use PBurggraf\BinaryUtilities\DataType\Integer;
use PBurggraf\BinaryUtilities\DataType\Short;
use PBurggraf\BinaryUtilities\EndianType\LittleEndian;
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
        $binaryFileCopy = '/tmp/' . md5(microtime());

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
        $binaryFileCopy = '/tmp/' . md5(microtime());

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



    public function testReadFirstSingleShortBigEndian()
    {
        $binaryUtility = new BinaryUtilities();

        $short = $binaryUtility
            ->setFile($this->binaryFile)
            ->read(Short::class)
            ->returnBuffer();

        static::assertCount(1, $short);
        static::assertEquals([17], $short);
    }

    public function testReadFirstThreeShortBigEndian()
    {
        $binaryUtility = new BinaryUtilities();
        $short = $binaryUtility
            ->setFile($this->binaryFile)
            ->read(Short::class)
            ->read(Short::class)
            ->read(Short::class)
            ->returnBuffer();

        static::assertCount(3, $short);
        static::assertEquals([17, 8755, 17493], $short);
    }

    public function testReadFirstThreeShortWithArrayBigEndian()
    {
        $binaryUtility = new BinaryUtilities();
        $short = $binaryUtility
            ->setFile($this->binaryFile)
            ->readArray(Short::class, 3)
            ->returnBuffer();

        static::assertCount(3, $short);
        static::assertEquals([17, 8755, 17493], $short);
    }


    public function testReadShortLittleEndian()
    {
        $binaryUtility = new BinaryUtilities();
        $short = $binaryUtility
            ->setFile($this->binaryFile)
            ->setEndian(LittleEndian::class)
            ->read(Short::class)
            ->returnBuffer();

        static::assertCount(1, $short);
        static::assertEquals([4352], $short);
    }

    public function testReadFirstThreeShortLittleEndian()
    {
        $binaryUtility = new BinaryUtilities();
        $short = $binaryUtility
            ->setFile($this->binaryFile)
            ->setEndian(LittleEndian::class)
            ->read(Short::class)
            ->read(Short::class)
            ->read(Short::class)
            ->returnBuffer();

        static::assertCount(3, $short);
        static::assertEquals([4352, 13090, 21828], $short);
    }

    public function testReadFirstThreeShortWithArrayLittleEndian()
    {
        $binaryUtility = new BinaryUtilities();
        $short = $binaryUtility
            ->setFile($this->binaryFile)
            ->setEndian(LittleEndian::class)
            ->readArray(Short::class, 3)
            ->returnBuffer();

        static::assertCount(3, $short);
        static::assertEquals([4352, 13090, 21828], $short);
    }



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
}
