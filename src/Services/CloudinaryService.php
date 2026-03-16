<?php

namespace App\Services;

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class CloudinaryService {
    
    public static function upload($fileTmpPath, $folderName) {
        // 1. Conectamos con la nube usando tus llaves secretas
        Configuration::instance([
            'cloud' => [
                'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
                'api_key'    => $_ENV['CLOUDINARY_API_KEY'],
                'api_secret' => $_ENV['CLOUDINARY_API_SECRET'],
            ],
            'url' => ['secure' => true]
        ]);

        $uploadApi = new UploadApi();

        try {
            // 2. Subimos el archivo y le decimos en qué subcarpeta guardarlo
            $result = $uploadApi->upload($fileTmpPath, [
                'folder' => 'portfolio/' . $folderName
            ]);
            
            return $result['secure_url'];
            
        } catch (\Exception $e) {
            throw new \Exception("Error al subir imagen a Cloudinary: " . $e->getMessage());
        }
    }
}