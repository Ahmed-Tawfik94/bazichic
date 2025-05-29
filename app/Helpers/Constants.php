<?php
namespace App\Helpers;
use App\Models\DocumentType;

class Constants
{
    const INSERT_SUCCESS = 1;
    const INSERT_FAILURE = -1;
    const ALREADY_EXIST = 2;
    const UPLOAD_IS_MISSING=4;
    const BANNER_FOLDER="uploads/images/banners/";
    const USER_FOLDER ="uploads/images/users/";
    const IMAGES_EXT=["jpg", "jpeg", "png"];
    const AUDIO_EXT=['ogg', 'mpeg', 'wav','mp3'];
}

