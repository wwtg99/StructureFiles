<?php
/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2016/2/29
 * Time: 16:54
 */

namespace Wwtg99\StructureFile\FileType;


use Wwtg99\StructureFile\Utils\FileHelper;

class ImageFile extends CommonFile {

    /**
     * ImageFile constructor.
     * @param string $path
     * @param string $content
     * @param string $ext
     */
    function __construct($path = '', $content = '', $ext = '')
    {
        parent::__construct($path, $content, $ext, FileHelper::getMimeFromExtension($ext));
    }

    /**
     * @param string $content
     * @param string $extension
     * @return ImageFile
     */
    public static function createFromString($content, $extension)
    {
        $img = new ImageFile();
        $img->content = (string)$content;
        $img->ext = FileHelper::formatExtension($extension);
        $img->mime = FileHelper::getMimeFromExtension($img->getExtension());
        return $img;
    }

    /**
     * @param string $mime
     * @return bool
     */
    public static function isImage($mime)
    {
        $s = strpos($mime, '/');
        if ($s > 0) {
            $pre = substr($mime, 0, $s);
            return $pre == 'image';
        }
        return false;
    }
} 