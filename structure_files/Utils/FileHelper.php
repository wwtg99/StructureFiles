<?php
/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2016/7/28
 * Time: 15:12
 */

namespace Wwtg99\StructureFile\Utils;


class FileHelper
{

    /**
     * @param string $ext
     * @return string
     */
    public static function getMimeFromExtension($ext)
    {
        $ext = self::formatExtension($ext);
        switch ($ext) {
            case "pdf": $ctype = "application/pdf"; break;
            case "exe": $ctype = "application/octet-stream"; break;
            case "zip": $ctype = "application/zip"; break;
            case "doc": $ctype = "application/msword"; break;
            case "docx": $ctype = "application/vnd.openxmlformats-officedocument.wordprocessingml.document"; break;
            case "xls": $ctype = "application/vnd.ms-excel"; break;
            case "xlsx": $ctype = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"; break;
            case "ppt": $ctype = "application/vnd.ms-powerpoint"; break;
            case "pptx": $ctype = "application/vnd.openxmlformats-officedocument.presentationml.presentation"; break;
            case "swf": $ctype = "application/x-shockwave-flash"; break;
            case "rmi":
            case "mid": $ctype = "audio/mid"; break;
            case "mp3": $ctype = "audio/mpeg"; break;
            case "wav": $ctype = "audio/x-wav"; break;
            case "bmp": $ctype = "image/bmp"; break;
            case "gif": $ctype = "image/gif"; break;
            case "png": $ctype = "image/png"; break;
            case "jpeg":
            case "jpe":
            case "jpg": $ctype = "image/jpeg"; break;
            case "tif":
            case "tiff": $ctype = "image/tiff"; break;
            case "svg": $ctype = "image/svg+xml"; break;
            case "ico": $ctype = "image/x-icon"; break;
            case "txt":
            case "csv":
            case "tsv": $ctype = "text/plain"; break;
            case "xml": $ctype = "text/xml"; break;
            case "htm":
            case "stm":
            case "html": $ctype = "text/html"; break;
            case "css": $ctype = "text/css"; break;
            default: $ctype = "application/force-download";
        }
        return $ctype;
    }

    /**
     * @param string $mime
     * @return string
     */
    public static function getExtensionFromMime($mime)
    {
        switch ($mime) {
            case 'application/pdf': $ext = 'pdf'; break;
            case 'application/zip': $ext = 'zip'; break;
            case 'application/msword': $ext = 'doc'; break;
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document': $ext = 'docx'; break;
            case 'application/vnd.ms-excel': $ext = 'xls'; break;
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': $ext = 'xlsx'; break;
            case 'application/vnd.ms-powerpoint': $ext = 'ppt'; break;
            case 'application/vnd.openxmlformats-officedocument.presentationml.presentation': $ext = 'pptx'; break;
            case 'application/x-shockwave-flash': $ext = 'swf'; break;
            case 'image/bmp': $ext = 'bmp'; break;
            case 'image/gif': $ext = 'gif'; break;
            case 'image/png': $ext = 'png'; break;
            case 'image/jpeg': $ext = 'jpg'; break;
            case 'image/tiff': $ext = 'tiff'; break;
            case 'image/svg+xml': $ext = 'svg'; break;
            case 'image/x-icon': $ext = 'ico'; break;
            case 'text/xml': $ext = 'xml'; break;
            case 'text/html': $ext = 'html'; break;
            case 'text/css': $ext = 'css'; break;
            default: $ext = '';
        }
        return $ext;
    }

    /**
     * Format extension without first dot.
     *
     * @param $extension
     * @return mixed
     */
    public static function formatExtension($extension)
    {
        $extension = preg_replace('/[^\w\.]/', '', trim(trim($extension), '.'));
        return $extension;
    }

    /**
     * Format path without last separator.
     *
     * @param string $path
     * @return string
     */
    public static function formatPath($path)
    {
        if ($path == DIRECTORY_SEPARATOR) {
            return $path;
        }
        return trim($path) ? rtrim(trim($path), DIRECTORY_SEPARATOR) : '';
    }

    /**
     * @param array $paths
     * @return string
     */
    public static function joinPathArray(array $paths)
    {
        $arr = [];
        for ($i = 0; $i < count($paths); $i++) {
            $p = self::formatPath($paths[$i]);
            if ($i == 0 && $p == DIRECTORY_SEPARATOR) {
                array_push($arr, '');
            } elseif ($p == '' || trim($p) == '' || $p == DIRECTORY_SEPARATOR) {
                continue;
            } else {
                array_push($arr, $p);
            }
        }
        return implode(DIRECTORY_SEPARATOR, $arr);
    }
}