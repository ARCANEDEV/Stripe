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
        $this->filePath   = __DIR__ . '/../data/test.png';
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
        $this->assertInstanceOf(
            'Arcanedev\\Stripe\\Resources\\FileUpload',
            $this->fileUpload
        );
    }

    /**
     * @test
     */
    public function testCanGetClassName()
    {
        $this->assertEquals('file', FileUpload::className());
    }

    /**
     * @test
     */
    public function testCanCreateFile()
    {
        $fp   = fopen($this->filePath, 'r');
        $file = $this->createFile($fp);
        fclose($fp);

        $this->assertEquals(95, $file->size);
        $this->assertEquals('png', $file->type);
    }

    /**
     * @test
     */
    public function testCanRetrieve()
    {
        $fp   = fopen($this->filePath, 'r');
        $file = $this->createFile($fp);
        fclose($fp);

        $this->fileUpload = FileUpload::retrieve($file->id);
        $this->assertEquals($this->fileUpload->id, $file->id);
        $this->assertEquals($this->fileUpload->purpose, $file->purpose);
        $this->assertEquals($this->fileUpload->type, $file->type);
    }

    /**
     * @test
     */
    public function testCanListAll()
    {
        $fp   = fopen($this->filePath, 'r');
        $this->createFile($fp);
        fclose($fp);

        $files = FileUpload::all();

        $this->assertTrue($files->isList());
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
        $file = $this->createFile($file);

        $this->assertEquals(95, $file->size);
        $this->assertEquals('png', $file->type);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other functions
     | ------------------------------------------------------------------------------------------------
     */
    private function createFile($file)
    {
        return FileUpload::create([
            'purpose' => 'dispute_evidence',
            'file'    => $file,
        ]);
    }
}
