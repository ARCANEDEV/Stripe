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
     * @param string            $id
     * @param array|string|null $options
     *
     * @return FileUploadInterface
     */
    public static function retrieve($id, $options = null);

    /**
     * Create/Upload a File
     *
     * @param array             $params
     * @param array|string|null $options
     *
     * @return FileUploadInterface
     */
    public static function create($params = [], $options = null);

    /**
     * List all uploaded files
     *
     * @param array             $params
     * @param array|string|null $options
     *
     * @return ListObjectInterface
     */
    public static function all($params = [], $options = null);
}
