<?php
/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2016/2/29
 * Time: 16:41
 */

namespace Wwtg99\StructureFile\FileType;


use Wwtg99\StructureFile\Base\AbstractFile;
use Wwtg99\StructureFile\Base\Downloadable;
use Wwtg99\StructureFile\Base\Printable;

class CommonFile extends AbstractFile implements Printable, Downloadable {

    /**
     * @param string $path
     * @param string $content
     * @param string $ext
     * @param string $mime
     */
    function __construct($path = '', $content = '', $ext = '', $mime = '')
    {
        if ($path) {
            $this->ext = self::formatExtension(strtolower(pathinfo($path, PATHINFO_EXTENSION)));
            $this->mime = self::getMimeFromExtension($this->ext);
            $this->path = $path;
        } elseif ($content) {
            if ($ext && $mime) {
                $this->ext = self::formatExtension($ext);
                $this->mime = $mime;
            } elseif ($ext && !$mime) {
                $this->ext = self::formatExtension($ext);
                $this->mime = self::getMimeFromExtension($this->ext);
            } elseif (!$ext && $mime) {
                $this->mime = $mime;
                $this->ext = self::getExtensionFromMime($this->mime);
            }
            $this->content = $content;
        }
    }

    /**
     * @param string $path
     * @return void
     */
    public function writeTo($path)
    {
        if ($this->content) {
            file_put_contents($path, $this->content);
        } elseif ($this->path && $path != $this->path) {
            copy($this->path, $path);
        }
    }

    /**
     * Download file in web browser.
     *
     * @param string $filename
     * @return void
     */
    public function download($filename = '')
    {
        $filename = $this->formatFilename($filename);
        if ($this->content) {
            $this->printHeader();
            header("Content-Disposition: attachment; filename=\"" . $filename . "\";" );
            ob_clean();
            echo $this->content;
        } elseif ($this->path) {
            $fsize = filesize($this->path);
            $this->printHeader($fsize);
            header("Content-Disposition: attachment; filename=\"" . $filename . "\";" );
            ob_clean();
            readfile($this->path);
        }
    }

    /**
     * Print file in web browser.
     *
     * @param string $filename
     * @return void
     */
    public function printContent($filename = '')
    {
        $filename = $this->formatFilename($filename);
        if ($this->content) {
            $this->printHeader();
            if ($filename) {
                header("Content-Disposition: inline; filename=\"" . $filename . "\";");
            }
            ob_clean();
            echo $this->content;
        } elseif ($this->path) {
            $fsize = filesize($this->path);
            $this->printHeader($fsize);
            if ($filename) {
                header("Content-Disposition: inline; filename=\"" . $filename . "\";");
            }
            ob_clean();
            readfile($this->path);
        }
    }
} 