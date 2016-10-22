<?php namespace Arcanedev\Stripe\Tests\Resources;

use Arcanedev\Stripe\Resources\FileUpload;
use Arcanedev\Stripe\Tests\StripeTestCase;
use CURLFile;

/**
 * Class     FileUploadTest
 *
 * @package  Arcanedev\Stripe\Tests\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
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
        $this->filePath   = realpath(__DIR__ . '/../data/test.png');
    }

    public function tearDown()
    {
        unset($this->fileUpload);
        unset($this->filePath);

        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(FileUpload::class, $this->fileUpload);
    }

    /** @test */
    public function it_can_get_class_name()
    {
        $this->assertSame('file', FileUpload::className());
    }

    /** @test */
    public function it_can_create()
    {
        $fp   = fopen($this->filePath, 'r');
        $file = $this->createFile($fp);
        fclose($fp);

        $this->assertSame(95, $file->size);
        $this->assertSame('png', $file->type);
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

        $this->assertSame(95, $file->size);
        $this->assertSame('png', $file->type);
    }

    /** @test */
    public function it_can_retrieve()
    {
        $fp   = fopen($this->filePath, 'r');
        $file = $this->createFile($fp);
        fclose($fp);

        $this->fileUpload = FileUpload::retrieve($file->id);
        $this->assertSame($this->fileUpload->id, $file->id);
        $this->assertSame($this->fileUpload->purpose, $file->purpose);
        $this->assertSame($this->fileUpload->type, $file->type);
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
