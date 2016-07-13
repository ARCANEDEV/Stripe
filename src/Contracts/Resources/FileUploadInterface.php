<?php namespace Arcanedev\Stripe\Contracts\Resources;

/**
 * Interface  FileUploadInterface
 *
 * @package   Arcanedev\Stripe\Contracts\Resources
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface FileUploadInterface
{
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
     */
    public static function retrieve($id, $options = null);

    /**
     * Create/Upload a File Upload.
     * @link   https://stripe.com/docs/api/php#create_file_upload
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return self|array
     */
    public static function create($params = [], $options = null);

    /**
     * List all uploaded files.
     * @link   https://stripe.com/docs/api/php#list_file_uploads
     *
     * @param  array|null         $params
     * @param  array|string|null  $options
     *
     * @return \Arcanedev\Stripe\Collection|array
     */
    public static function all($params = [], $options = null);
}
