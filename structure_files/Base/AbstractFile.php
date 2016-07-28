<?php
/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2016/2/29
 * Time: 15:42
 */

namespace Wwtg99\StructureFile\Base;


abstract class AbstractFile {

    /**
     * @param string $path
     * @return void
     */
    abstract public function writeTo($path);

    /**
     * @var string
     */
    protected $mime = '';

    /**
     * @var string
     */
    protected $ext = '';

    /**
     * @var string
     */
    protected $content = '';

    /**
     * @var string
     */
    protected $path = '';

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->ext;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        if ($this->path)
        {
            return filesize($this->path);
        } elseif ($this->content) {
            return mb_strlen($this->content);
        }
        return 0;
    }

    /**
     * @param string $filename
     * @return string
     */
    public function formatFilename($filename)
    {
        if (!$filename) {
            $filename = basename($this->path);
        } else {
            if (strrpos($filename, '.') === false) {
                $filename .= ($this->ext ? '.' . $this->ext : '');
            }
        }
        if (!$filename) {
            $filename = 'download' . ($this->ext ? '.' . $this->ext : '');
        }
        return $filename;
    }

    /**
     * @param int $fsize
     */
    protected function printHeader($fsize = 0)
    {
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        header("Content-Type: " . $this->getMime());
        header("Content-Transfer-Encoding: binary");
        if ($fsize) {
            header("Content-Length: " . $fsize);
        }
    }
}
