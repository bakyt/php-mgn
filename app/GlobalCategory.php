<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GlobalCategory extends Model
{
    protected $fillable=[
        "parent_id",
        "moderator_id",
        "order",
        "name",
        "name_single",
        "image",
        "author_id",
        "status",
        "description",
        'payment_time',
        "features",
        "keywords",
        "type",
        "state"
    ];
    protected $guarded = ['id'];

    public function parent()
    {
        return $this->belongsTo('App\GlobalCategory', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\GlobalCategory', 'parent_id');
    }

    /**
     * @param $image
     * @param $folderPath
     * @param array $size
     * @param bool $thump
     * @return array
     */
    public static function uploadImage($image, $folderPath, $size, $thump=false, $crop=false){
        ini_set('gd.jpeg_ignore_warning', true);
        if(is_array($_FILES)) {
            if(!is_array($image['tmp_name'])) {
                $file = $image['tmp_name'];
                $sourceProperties = getimagesize($file);
                $fileNewName = date('Ymdhis') . time();
                if (!file_exists("storage/" . $folderPath)) {
                    mkdir("storage/" . $folderPath);
                }
                //$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
                $imageType = $sourceProperties[2];
                switch ($imageType) {
                    case IMAGETYPE_PNG:
                        $imageResourceId = imagecreatefrompng($file);
                        break;

                    case IMAGETYPE_GIF:
                        $imageResourceId = imagecreatefromgif($file);
                        break;


                    case IMAGETYPE_JPEG:
                        $imageResourceId = imagecreatefromjpeg($file);
                        break;


                    default:
                        return [-1, "File type must be JPEG, PNG or GIF"];
                }

//                if ($thump) switch ($imageType) {
//                    case IMAGETYPE_PNG:
//                        $imageResourceId = imagecreatefrompng($file);
//                        $targetLayer = self::imageResize($imageResourceId, $sourceProperties[0], $sourceProperties[1]);
//                        imagepng($targetLayer, "storage/" . $folderPath . $fileNewName . "_thump." . $ext);
//                        break;
//
//
//                    case IMAGETYPE_GIF:
//                        $imageResourceId = imagecreatefromgif($file);
//                        $targetLayer = self::imageResize($imageResourceId, $sourceProperties[0], $sourceProperties[1]);
//                        imagegif($targetLayer, "storage/" . $folderPath . $fileNewName . "_thump." . $ext);
//                        break;
//
//
//                    case IMAGETYPE_JPEG:
//                        $imageResourceId = imagecreatefromjpeg($file);
//                        $targetLayer = self::imageResize($imageResourceId, $sourceProperties[0], $sourceProperties[1]);
//                        imagejpeg($targetLayer, "storage/" . $folderPath . $fileNewName . "_thump." . $ext);
//                        break;
//
//
//                    default:
//                        return [-1, "File type must be JPEG, PNG or GIF"];
//                }
                if(!$crop) imagejpeg(self::resizeImage($imageResourceId, $image['tmp_name'], $size[0], $size[1]), "storage/" . $folderPath . $fileNewName . ".jpeg");
                else imagejpeg(self::imageResize($imageResourceId, $image['tmp_name'], $crop, $crop), "storage/" . $folderPath . $fileNewName . ".jpeg");
                return [0, $folderPath . $fileNewName . ".jpeg"];
            }
            else {
                $images=array();
                for($i=0; $i<count($image['tmp_name']); $i++) {
                    $file = $image['tmp_name'][$i];
                    $sourceProperties = getimagesize($file);
                    $fileNewName = date('Ymdhis') . time().$i;
                    if (!file_exists("storage/" . $folderPath)) {
                        mkdir("storage/" . $folderPath);
                    }
                    //$ext = pathinfo($image['name'][$i], PATHINFO_EXTENSION);
                    $imageType = $sourceProperties[2];
                    switch ($imageType) {
                        case IMAGETYPE_PNG:
                            $imageResourceId = imagecreatefrompng($file);
                            break;
                        case IMAGETYPE_GIF:
                            $imageResourceId = imagecreatefromgif($file);
                            break;
                        case IMAGETYPE_JPEG:
                            $imageResourceId = imagecreatefromjpeg($file);
                            break;
                        default:
                            return [-1, "File type must be JPEG, PNG or GIF"];
                    }

//                    if ($thump) switch ($imageType) {
//                        case IMAGETYPE_PNG:
//                            $imageResourceId = imagecreatefrompng($file);
//                            $targetLayer = self::imageResize($imageResourceId, $sourceProperties[0], $sourceProperties[1]);
//                            imagepng($targetLayer, "storage/" . $folderPath . $fileNewName . "_thump." . $ext);
//                            break;
//
//
//                        case IMAGETYPE_GIF:
//                            $imageResourceId = imagecreatefromgif($file);
//                            $targetLayer = self::imageResize($imageResourceId, $sourceProperties[0], $sourceProperties[1]);
//                            imagegif($targetLayer, "storage/" . $folderPath . $fileNewName . "_thump." . $ext);
//                            break;
//
//
//                        case IMAGETYPE_JPEG:
//                            $imageResourceId = imagecreatefromjpeg($file);
//                            $targetLayer = self::imageResize($imageResourceId, $sourceProperties[0], $sourceProperties[1]);
//                            imagejpeg($targetLayer, "storage/" . $folderPath . $fileNewName . "_thump." . $ext);
//                            break;
//
//
//                        default:
//                            return [-1, "File type must be JPEG, PNG or GIF"];
//                    }
                    imagejpeg(self::resizeImage($imageResourceId, $image['tmp_name'][$i], $size[0], $size[1]), "storage/" . $folderPath . $fileNewName . ".jpeg");
                    array_push($images, $folderPath . $fileNewName . ".jpeg");
                }
                return [0, $images];
            }
        }
        else return [-1, "Wrong file!"];
    }
    public static function imageResize($imageResourceId, $source_file, $max_width,$max_height) {

            $imgsize = getimagesize($source_file);
            $width = $imgsize[0];
            $height = $imgsize[1];

            $dst_img = imagecreatetruecolor($max_width, $max_height);

            $width_new = $height * $max_width / $max_height;
            $height_new = $width * $max_height / $max_width;
            //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
            if($width_new > $width){
                //cut point by height
                $h_point = (($height - $height_new) / 2);
                //copy image
                imagecopyresampled($dst_img, $imageResourceId, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
            }else{
                //cut point by width
                $w_point = (($width - $width_new) / 2);
                imagecopyresampled($dst_img, $imageResourceId, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
            }

        return $dst_img;
    }
    /**
     * Resize image - preserve ratio of width and height.
     * @param $image
     * @param string $sourceImage path to source JPEG image
     * @param int $maxWidth maximum width of final image (value 0 - width is optional)
     * @param int $maxHeight maximum height of final image (value 0 - height is optional)
     * @param int $quality quality of final image (0-100)
     * @return mixed
     */
    public static function resizeImage($image, $sourceImage, $maxWidth, $maxHeight)
    {

        // Get dimensions of source image.
        list($origWidth, $origHeight) = getimagesize($sourceImage);

        if ($maxWidth == 0)
        {
            $maxWidth  = $origWidth;
        }

        if ($maxHeight == 0)
        {
            $maxHeight = $origHeight;
        }

        // Calculate ratio of desired maximum sizes and original sizes.
        $widthRatio = $maxWidth / $origWidth;
        $heightRatio = $maxHeight / $origHeight;

        // Ratio used for calculating new image dimensions.
        $ratio = min($widthRatio, $heightRatio);

        // Calculate new image dimensions.
        $newWidth  = (int)$origWidth  * $ratio;
        $newHeight = (int)$origHeight * $ratio;

        // Create final image with new dimensions.
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        // Free up the memory.
        imagedestroy($image);

        return $newImage;
    }
}
