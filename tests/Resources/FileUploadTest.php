<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\FileUpload;
use CURLFile;

use Arcanedev\Stripe\Tests\StripeTestCase;

class FileUploadTest extends StripeTestCase
{
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
        $this->filePath   = $this->getFilePath();
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
    /**
     * @test
     */
    public function testCanBeInstantiate()
    {
        $this->assertInstanceOf('Arcanedev\\Stripe\\Resources\\FileUpload', $this->fileUpload);
    }

    /**
     * @test
     */
    public function testCanCreateFile()
    {
        $fp   = fopen($this->filePath, 'r');
        $file = FileUpload::create([
            'purpose' => 'dispute_evidence',
            'file'    => $fp,
        ]);
        fclose($fp);

        $this->assertEquals(95, $file->size);
        $this->assertEquals('image/png', $file->mimetype);
    }

    /**
     * @test
     */
    public function testCanCreateCurlFile()
    {
        if (! class_exists('CurlFile')) {
            // Older PHP versions don't support this
            return;
        }

        $file = new CurlFile($this->filePath);
        $file = FileUpload::create([
            'purpose' => 'dispute_evidence',
            'file'    => $file,
        ]);

        $this->assertEquals(95, $file->size);
        $this->assertEquals('image/png', $file->mimetype);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function getFilePath()
    {
        return __DIR__ . '/../data/test.png';
    }
}
