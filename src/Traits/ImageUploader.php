<?php

namespace App\Traits;

use App\Services\CloudinaryService;

trait ImageUploader {

  public function HandleImageUpload($fileInputName, $folderName) {
    if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
      $fileExt = strtolower(pathinfo($_FILES[$fileInputName]['name'], PATHINFO_EXTENSION ));
      $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];

      if (!in_array($fileExt, $allowedExts)) {

        throw new \Exception("Formato no válido. Solo JPG, PNG o WEBP");

      }

      return CloudinaryService::upload($_FILES[$fileInputName]['tmp_name'], $folderName);
    }

    return null;
  }

}