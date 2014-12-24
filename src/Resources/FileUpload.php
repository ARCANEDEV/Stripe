<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\FileUploadInterface;
use Arcanedev\Stripe\Resource;
use Arcanedev\Stripe\Stripe;

/**
 * File Upload Object
 * @link https://stripe.com/docs/guides/file-upload
 *
 * @property string id
 * @property int    created
 * @property int    size
 * @property string purpose
 * @property string url
 * @property string mimetype [application/pdf|image/jpeg|image/png]
 */
class FileUpload extends Resource implements FileUploadInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public static function baseUrl()
    {
        return Stripe::getUploadBaseUrl();
    }

    public static function className($class = '')
    {
        return 'file';
    }

    /**
     * Get the endpoint URL for the given class.
     *
     * @param string $class
     *
     * @return string
     */
    public static function classUrl($class = '')
    {
        return "/v1/files";
    }

    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve a File
     *
     * @param string      $id
     * @param string|null $apiKey
     *
     * @return FileUpload
     */
    public static function retrieve($id, $apiKey = null)
    {
        return self::scopedRetrieve(get_class(), $id, $apiKey);
    }

    /**
     * Create/Upload a File
     *
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return FileUpload
     */
    public static function create($params = [], $apiKey = null)
    {
        return self::scopedCreate(get_class(), $params, $apiKey);
    }
}
