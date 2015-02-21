<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\FileUploadInterface;
use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Requestor;
use Arcanedev\Stripe\Resource;
use Arcanedev\Stripe\Stripe;
use Arcanedev\Stripe\Utilities\RequestOptions;

/**
 * File Upload Object
 * @link https://stripe.com/docs/guides/file-upload
 *
 * @property string id
 * @property string object
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
    /**
     * Get FileUpload base URL
     *
     * @return string
     */
    public static function baseUrl()
    {
        return Stripe::getUploadBaseUrl();
    }

    /**
     * Get object name
     *
     * @param  string $class
     *
     * @return string
     */
    public static function className($class = '')
    {
        return 'file';
    }

    /**
     * Get the endpoint URL for the given class.
     *
     * @param  string $class
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
     * @param  string            $id
     * @param  array|string|null $options
     *
     * @return FileUpload
     */
    public static function retrieve($id, $options = null)
    {
        $opts    = RequestOptions::parse($options);

        // TODO: Refactor retrieve() method
        $apiKey  = $opts->getApiKey();
        $file    = new self($id, $apiKey);

        list($response, $apiKey) = Requestor::make($apiKey, self::baseUrl())
            ->get(self::END_POINT . '/' . $id, $file->retrieveParameters);

        $file->refreshFrom($response, $apiKey);

        return $file;
    }

    /**
     * Create/Upload a File
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return FileUpload|array
     */
    public static function create($params = [], $options = null)
    {
        return parent::scopedCreate($params, $options);
    }

    /**
     * List all uploaded files
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Collection|array
     */
    public static function all($params = [], $options = null)
    {
        return parent::scopedAll($params, $options);
    }
}
