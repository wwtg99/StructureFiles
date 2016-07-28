<?php
/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2016/2/29
 * Time: 16:59
 */

namespace Wwtg99\StructureFile\FileType;


use Wwtg99\StructureFile\Base\AbstractFile;
use Wwtg99\StructureFile\Base\Downloadable;
use Wwtg99\StructureFile\SectionFile\Section;
use Wwtg99\StructureFile\SectionFile\SectionFile;

class ExcelFile extends AbstractFile implements Downloadable {

    /**
     * @var \PHPExcel
     */
    private $excel;

    /**
     * @var string
     */
    private $writeType = 'Excel2007'; //CSV, Excel2007, Excel5, HTML, OpenDocument, PDF, PDF_DomPDF, PDF_mPDF, PDF_tcPDF

    /**
     * @param string $filename
     * @return mixed
     */
    public function download($filename = '')
    {
        $filename = $this->formatFilename($filename);
        self::printHeader();
        header("Content-Disposition: attachment;filename='$filename'");
        $objWriter = \PHPExcel_IOFactory::createWriter($this->excel, $this->writeType);
        $objWriter->save('php://output');
    }

    /**
     * @param string $path
     * @return void
     */
    public function writeTo($path)
    {
        $objWriter = \PHPExcel_IOFactory::createWriter($this->excel, $this->writeType);
        $objWriter->save($path);
    }

    /**
     * ExcelFile constructor.
     * @param string $path
     * @param string $ext
     */
    function __construct($path, $ext = 'xlsx')
    {
        if (file_exists($path)) {
            $this->excel = \PHPExcel_IOFactory::load($path);
            $this->ext = $ext;
            $this->mime = self::getMimeFromExtension($this->ext);
        }
    }

    /**
     * @return \PHPExcel
     */
    public function getData()
    {
        return $this->excel;
    }

    /**
     * @return array
     */
    public function getArray()
    {
        $data = [];
        $sheet = $this->excel->getActiveSheet();
        $hrow = $sheet->getHighestRow();
        $hcol = $sheet->getHighestColumn();
        $hcoli = \PHPExcel_Cell::columnIndexFromString($hcol);
        for ($r = 1; $r <= $hrow; $r++) {
            $dr = [];
            for ($c = 0; $c <= $hcoli; $c++) {
                $d = $sheet->getCellByColumnAndRow($c, $r)->getValue();
                array_push($dr, $d);
            }
            array_push($data, $dr);
        }
        return $data;
    }

    /**
     * @return string
     */
    public function getWriteType()
    {
        return $this->writeType;
    }

    /**
     * @param string $writeType
     * @return $this
     */
    public function setWriteType($writeType)
    {
        $this->writeType = $writeType;
        return $this;
    }

    /**
     * @param SectionFile $sectionFile
     * @param array $conf
     * @return ExcelFile
     */
    public static function createFromSection(SectionFile $sectionFile, $conf = [])
    {
        $data = [];
        //handle sections
        foreach ($sectionFile->getSections() as $i => $section) {
            if ($section instanceof Section) {
                //show name
                if ($section->getShowName()) {
                    $name = ExcelFile::getName($section);
                    array_push($data, $name);
                }
                //depends on type
                if ($section->getType() == Section::TSV_SECTION) {
                    $data = array_merge($data, ExcelFile::createTsv($section));
                } elseif ($section->getType() == Section::KV_SECTION) {
                    $data = array_merge($data, ExcelFile::createKv($section));
                } elseif ($section->getType() == Section::RAW_SECTION) {
                    $data = array_merge($data, ExcelFile::createRaw($section));
                }
                array_push($data, []);
            }
        }
        $excel = new ExcelFile('');
        $excel->excel = ExcelFile::getExcel($data);
        $excel->setProperties($conf);
        return $excel;
    }

    /**
     * @param array $data
     * @param array $head: [['field'=>'f1', 'title'=>'t1'], ...]
     * @param string $null
     * @param array $conf
     * @return ExcelFile
     */
    public static function createFromData($data, $head = [], $null = ' - ', $conf = [])
    {
        $exdata = [];
        if ($head) {
            $head_field = [];
            $head_title = [];
            foreach ($head as $h) {
                array_push($head_field, $h['field']);
                array_push($head_title, $h['title']);
            }
            array_push($exdata, $head_title);
            foreach ($data as $d) {
                $line = [];
                foreach ($head_field as $f) {
                    if (array_key_exists($f, $d)) {
                        array_push($line, $d[$f]);
                    } else {
                        array_push($line, $null);
                    }
                }
                array_push($exdata, $line);
            }
        } else {
            $exdata = $data;
        }
        $excel = new ExcelFile('');
        $excel->excel = ExcelFile::getExcel($exdata);
        $excel->setProperties($conf);
        return $excel;
    }

    /**
     * Get PHPExcel from array.
     *
     * @param array $data
     * @return \PHPExcel
     * @throws \PHPExcel_Exception
     */
    private static function getExcel(array $data)
    {
        $excel = new \PHPExcel();
        $row = 1;
        $col = 0;
        $sheet = $excel->getActiveSheet();
        foreach ($data as $d) {
            if (is_array($d)) {
                foreach ($d as $dd) {
                    $sheet->getCellByColumnAndRow($col, $row)->setValue((string)$dd);
                    $col++;
                }
                $col = 0;
            } else {
                $sheet->getCellByColumnAndRow($col, $row)->setValue((string)$d);
            }
            $row++;
        }
        return $excel;
    }

    /**
     * @param Section $section
     * @return string
     */
    private static function getName(Section $section)
    {
        static $i = 1;
        $name = '';
        if ($section->getShowName()) {
            $name = $section->getName();
            if (!$name) {
                $name = "Section " . $i++;
            }
            $name = '[' . $name . ']';
        }
        return $name;
    }

    /**
     * @param Section $section
     * @return array
     */
    private static function createKv(Section $section)
    {
        //skip
        $skip = $section->getSkip();
        //data
        $darr = [];
        foreach ($section->getData() as $h => $d) {
            $line = [];
            if (!in_array($h, $skip)) {
                $v = $d;
                if (is_null($v)) {
                    $v = $section->getNull();
                }
                //prefix
                if ($section->getPrefix()) {
                    array_push($line, $section->getPrefix());
                }
                //head
                if ($section->getHead()) {
                    foreach ($section->getHead() as $sh) {
                        $hf = $sh['field'];
                        if ($hf == $h) {
                            array_push($line, $sh['title']);
                            break;
                        }
                    }
                } else {
                    array_push($line, $h);
                }
                array_push($line, $v);
                //postfix
                if ($section->getPostfix()) {
                    array_push($line, $section->getPostfix());
                }
                array_push($darr, $line);
            }
        }
        return $darr;
    }

    /**
     * @param Section $section
     * @return array
     */
    private static function createTsv(Section $section)
    {
        $out = [];
        if ($section->getHead()) {
            //skip
            $skip = $section->getSkip();
            $harr = [];
            $htitle = [];
            foreach ($section->getHead() as $h) {
                $hf = $h['field'];
                if (!in_array($hf, $skip)) {
                    array_push($harr, $h);
                    array_push($htitle, $h['title']);
                }
            }
            //show head
            if ($section->getShowHead()) {
                array_push($out, $htitle);
            }
            //get data
            $darr = ExcelFile::getTsvData($section, $harr);
            $out = array_merge($out, $darr);
        } else {
            //get data
            $darr = ExcelFile::getTsvData($section);
            $out = array_merge($out, $darr);
        }
        return $out;
    }

    /**
     * @param Section $section
     * @return array
     */
    private static function createRaw(Section $section)
    {
        return $section->getData();
    }

    /**
     * @param Section $section
     * @param array $harr
     * @return array
     */
    private static function getTsvData(Section $section, $harr = array())
    {
        $darr = [];
        foreach ($section->getData() as $j => $d) {
            $line = [];
            //prefix
            if ($section->getPrefix()) {
                array_push($line, $section->getPrefix());
            }
            //data
            if ($harr) {
                foreach ($harr as $h) {
                    $hf = $h['field'];
                    if (array_key_exists($hf, $d)) {
                        $v = $d[$hf];
                        if (is_null($v)) {
                            array_push($line, $section->getNull());
                        } else {
                            array_push($line, $d[$hf]);
                        }
                    } else {
                        array_push($line, $section->getNull());
                    }
                }
            } else {
                foreach ($d as $dd) {
                    array_push($line, $dd);
                }
            }
            //postfix
            if ($section->getPostfix()) {
                array_push($line, $section->getPostfix());
            }
            array_push($darr, $line);
        }
        return $darr;
    }

    /**
     * @param array $conf
     */
    private function setProperties($conf = [])
    {
        if (array_key_exists('creator', $conf)) {
            $this->excel->getProperties()->setCreator($conf['creator']);
        }
        if (array_key_exists('title', $conf)) {
            $this->excel->getProperties()->setTitle($conf['title']);
        }
        if (array_key_exists('subject', $conf)) {
            $this->excel->getProperties()->setSubject($conf['subject']);
        }
        if (array_key_exists('keywords', $conf)) {
            $this->excel->getProperties()->setKeywords($conf['keywords']);
        }
        if (array_key_exists('description', $conf)) {
            $this->excel->getProperties()->setDescription($conf['description']);
        }
        if (array_key_exists('company', $conf)) {
            $this->excel->getProperties()->setCompany($conf['company']);
        }
    }
} 