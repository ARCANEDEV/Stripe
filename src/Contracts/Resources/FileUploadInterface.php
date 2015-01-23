<?php namespace Arcanedev\Stripe\Contracts\Resources;

use Arcanedev\Stripe\ListObject;
use Arcanedev\Stripe\Resources\FileUpload;

interface FileUploadInterface
{
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
    public static function retrieve($id, $options = null);

    /**
     * Create/Upload a File
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return FileUpload
     */
    public static function create($params = [], $options = null);

    /**
     * List all uploaded files
     *
     * @param  array|null        $params
     * @param  array|string|null $options
     *
     * @return ListObject|array
     */
    public static function all($params = [], $options = null);
}
