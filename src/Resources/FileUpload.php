<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Contracts\Resources\FileUploadInterface;
use Arcanedev\Stripe\Http\RequestOptions;
use Arcanedev\Stripe\Http\Requestor;
use Arcanedev\Stripe\Stripe;
use Arcanedev\Stripe\StripeResource;

/**
 * Class     FileUpload
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/api/php#file_upload_object
 * @link     https://stripe.com/docs/guides/file-upload
 *
 * @property  string  id
 * @property  string  object   // 'file_upload'
 * @property  int     created  // timestamp
 * @property  string  purpose
 * @property  int     size
 * @property  string  type      // [pdf|jpeg|png]
 * @property  string  url
 */
class FileUpload extends StripeResource implements FileUploadInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const END_POINT = '/v1/files';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get FileUpload base URL.
     *
     * @return string
     */
    public static function baseUrl()
    {
        return Stripe::getUploadBaseUrl();
    }

    /**
     * Get object name.
     *
     * @param  string  $class
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
     * @param  string  $class
     *
     * @return string
     */
    public static function classUrl($class = '')
    {
        return self::END_POINT;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Retrieve a File Upload.
     * @link   https://stripe.com/docs/api/php#retrieve_file_upload
     *
     * @param  string             $id
     * @param  array|string|null  $options
     *
     * @return self
     *
     * @todo: Refactor the FileUpload::retrieve() method
     */
    public static function retrieve($id, $options = null)
    {
        $apiKey  = RequestOptions::parse($options)->getApiKey();
        $file    = new self($id, $apiKey);

        /** @var \Arcanedev\Stripe\Http\Response $response */
        list($response, $apiKey) = Requestor::make($apiKey, self::baseUrl())
            ->get(self::END_POINT . '/' . $id, $file->retrieveParameters);

        $file->refreshFrom($response->getJson(), $apiKey);
        $file->setLastResponse($response);

        return $file;
    }

    /**
     * Create/Upload a File Upload.
     * @link   https://stripe.com/docs/api/php#create_file_upload
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self|array
     */
    public static function create($params = [], $options = null)
    {
        return self::scopedCreate($params, $options);
    }

    /**
     * List all uploaded files.
     * @link   https://stripe.com/docs/api/php#list_file_uploads
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|self[]
     */
    public static function all($params = [], $options = null)
    {
        return self::scopedAll($params, $options);
    }
}
