<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\FileUploadInterface;
use Arcanedev\Stripe\Requestor;
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
 * @property string type [pdf|jpeg|png]
 */
class FileUpload extends Resource implements FileUploadInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    const END_POINT = "/v1/files";

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
        return self::END_POINT;
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
        // TODO: Refactor retrieve() method
        $file = new self($id, $apiKey);
        $url  = self::END_POINT . '/' . $id;

        list($response, $apiKey) = Requestor::make($apiKey, self::baseUrl())
            ->get($url, $file->retrieveParameters);

        $file->refreshFrom($response, $apiKey);

        return $file;
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
        return self::scopedCreate($params, $apiKey);
    }
}
