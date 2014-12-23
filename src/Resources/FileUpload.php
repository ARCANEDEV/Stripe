<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\FileUploadInterface;
use Arcanedev\Stripe\Resource;
use Arcanedev\Stripe\Stripe;

class FileUpload extends Resource implements FileUploadInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public static function baseUrl()
    {
        return Stripe::$apiUploadBase;
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
     * @param string      $id The ID of the file upload to retrieve.
     * @param string|null $apiKey
     *
     * @return FileUpload
     */
    public static function retrieve($id, $apiKey = null)
    {
        $class = get_class();

        return self::scopedRetrieve($class, $id, $apiKey);
    }

    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return FileUpload The created file upload.
     */
    public static function create($params = null, $apiKey = null)
    {
        $class = get_class();

        return self::scopedCreate($class, $params, $apiKey);
    }
}