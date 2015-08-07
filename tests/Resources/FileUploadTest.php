<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\FileUpload;
use Arcanedev\Stripe\Tests\StripeTestCase;
use CURLFile;

/**
 * Class FileUploadTest
 * @package Arcanedev\Stripe\Tests\Resources
 */
class FileUploadTest extends StripeTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const FILE_UPLOAD_CLASS = 'Arcanedev\\Stripe\\Resources\\FileUpload';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var FileUpload */
    private $fileUpload;

    /** @var string */
    private $filePath = '';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->fileUpload = new FileUpload;
        $this->filePath   = realpath(__DIR__ . '/../data/test.png');
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->fileUpload);
        unset($this->filePath);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(self::FILE_UPLOAD_CLASS, $this->fileUpload);
    }

    /** @test */
    public function it_can_get_class_name()
    {
        $this->assertEquals('file', FileUpload::className());
    }

    /** @test */
    public function it_can_create()
    {
        $fp   = fopen($this->filePath, 'r');
        $file = $this->createFile($fp);
        fclose($fp);

        $this->assertEquals(95, $file->size);
        $this->assertEquals('png', $file->type);
    }

    /** @test */
    public function it_can_create_curl_file()
    {
        if (! class_exists('CurlFile')) {
            // Older PHP versions don't support this
            return;
        }

        $file = new CurlFile($this->filePath);
        $file = $this->createFile($file);

        $this->assertEquals(95, $file->size);
        $this->assertEquals('png', $file->type);
    }

    /** @test */
    public function it_can_retrieve()
    {
        $fp   = fopen($this->filePath, 'r');
        $file = $this->createFile($fp);
        fclose($fp);

        $this->fileUpload = FileUpload::retrieve($file->id);
        $this->assertEquals($this->fileUpload->id, $file->id);
        $this->assertEquals($this->fileUpload->purpose, $file->purpose);
        $this->assertEquals($this->fileUpload->type, $file->type);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create file
     *
     * @param mixed $file
     *
     * @return FileUpload|array
     */
    private function createFile($file)
    {
        return FileUpload::create([
            'purpose' => 'dispute_evidence',
            'file'    => $file,
        ]);
    }
}
