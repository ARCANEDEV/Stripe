<?php namespace Arcanedev\Stripe\Resources;

use Arcanedev\Stripe\Collection;
use Arcanedev\Stripe\Contracts\Resources\FileUploadInterface;
use Arcanedev\Stripe\Http\Requestor;
use Arcanedev\Stripe\StripeResource;
use Arcanedev\Stripe\Stripe;
use Arcanedev\Stripe\Utilities\RequestOptions;

/**
 * Class     FileUpload
 *
 * @package  Arcanedev\Stripe\Resources
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 * @link     https://stripe.com/docs/guides/file-upload
 *
 * @property  string  id
 * @property  string  object
 * @property  int     created
 * @property  int     size
 * @property  string  purpose
 * @property  string  url
 * @property  string  type      // [pdf|jpeg|png]
 *
 * @todo:     Update the properties.
 */
class FileUpload extends StripeResource implements FileUploadInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    const END_POINT = '/v1/files';

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
     * @return self
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
     * @return self|array
     */
    public static function create($params = [], $options = null)
    {
        return self::scopedCreate($params, $options);
    }

    /**
     * List all uploaded files
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return Collection|self[]
     */
    public static function all($params = [], $options = null)
    {
        return self::scopedAll($params, $options);
    }
}
