<?php namespace Arcanedev\Stripe\Contracts\Resources;

interface FileUploadInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  CRUD Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param string      $id The ID of the file upload to retrieve.
     * @param string|null $apiKey
     *
     * @return FileUploadInterface
     */
    public static function retrieve($id, $apiKey = null);

    /**
     * @param array|null  $params
     * @param string|null $apiKey
     *
     * @return FileUploadInterface The created file upload.
     */
    public static function create($params = null, $apiKey = null);
}
