<?php

namespace PBurggraf\BinaryUtilities\Test;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PBurggraf\BinaryUtilities\BinaryUtilities;
use PBurggraf\BinaryUtilities\DataType\Integer;
use PBurggraf\BinaryUtilities\EndianType\BigEndian;
use PBurggraf\BinaryUtilities\EndianType\LittleEndian;
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

    public function testReadNonExistingFile()
    {
        $this->expectException(FileDoesNotExistsException::class);

        $binaryUtility = new BinaryUtilities();
        $binaryUtility->setFile('nonExistingFile.bin');
    }
}
