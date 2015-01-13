<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\Contracts\ListObjectInterface;

/**
 * File Upload Object Interface
 * @link https://stripe.com/docs/guides/file-upload
 *
 * @property string id
 * @property int    created
 * @property int    size
 * @property string purpose
 * @property string url
 * @property string mimetype [application/pdf|image/jpeg|image/png]
 */
interface FileUploadInterface
{
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
     * @return FileUploadInterface
     */
    public static function retrieve($id, $apiKey = null);

    /**
     * Create/Upload a File
     *
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return FileUploadInterface
     */
    public static function create($params = [], $apiKey = null);

    /**
     * List all uploaded files
     *
     * @param array       $params
     * @param string|null $apiKey
     *
     * @return ListObjectInterface
     */
    public static function all($params = [], $apiKey = null);
}
