<?php
/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2016/2/29
 * Time: 16:54
 */

namespace StructureFile\FileType;


class ImageFile extends CommonFile {

    /**
     * ImageFile constructor.
     * @param string $path
     * @param string $content
     * @param string $ext
     */
    function __construct($path = '', $content = '', $ext = '')
    {
        parent::__construct($path, $content, $ext, self::getMimeFromExtension($ext));
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
        $img->ext = self::formatExtension($extension);
        $img->mime = self::getMimeFromExtension($img->getExtension());
        return $img;
    }

    /**
     * @param string $mime
     * @return bool
     */
    public static function isImage($mime)
    {
        return in_array($mime, ['image/jpg', 'image/jpeg', 'image/png', 'image/gif', 'image/tiff']);
    }
} 