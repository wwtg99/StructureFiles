<?php
/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2016/2/29
 * Time: 16:22
 */

class TestFile extends PHPUnit_Framework_TestCase {

    public static function setUpBeforeClass()
    {
        date_default_timezone_set('Asia/Shanghai');
        require '../structure_files/Base/Downloadable.php';
        require '../structure_files/Base/Printable.php';
        require '../structure_files/Base/AbstractFile.php';
        require '../structure_files/SectionFile/Section.php';
        require '../structure_files/SectionFile/SectionFile.php';
        require '../structure_files/FileType/CommonFile.php';
        require '../structure_files/FileType/TxtFile.php';
        require '../structure_files/FileType/ImageFile.php';
        require '../structure_files/FileType/ExcelFile.php';
        require '../vendor/autoload.php';
    }

    public function testSec()
    {
        $sec1 = new \Wwtg99\StructureFile\SectionFile\Section(\Wwtg99\StructureFile\SectionFile\Section::KV_SECTION, 's1', ['aa', 'bb']);
        $sec2 = new \Wwtg99\StructureFile\SectionFile\Section(\Wwtg99\StructureFile\SectionFile\Section::RAW_SECTION, 's2', ['cc', 'dd']);
        $sf = new \Wwtg99\StructureFile\SectionFile\SectionFile([$sec1, $sec2]);
        $exp = ['s1', 's2'];
        foreach ($sf as $i => $s) {
            $this->assertEquals($exp[$i], $s->getName());
        }
    }

    public function testFormatFilename()
    {
        $f = new \Wwtg99\StructureFile\FileType\CommonFile('', 'aa', 'txt');
        $tests = ['file1'=>'file1.txt', 'file2.csv'=>'file2.csv', ''=>'download.txt'];
        foreach ($tests as $test => $exp) {
            $cf = $f->formatFilename($test);
            $this->assertEquals($exp, $cf);
        }
        $f2 = new \Wwtg99\StructureFile\FileType\CommonFile();
        $tests = ['file1'=>'file1', 'file2.csv'=>'file2.csv', ''=>'download'];
        foreach ($tests as $test => $exp) {
            $cf = $f2->formatFilename($test);
            $this->assertEquals($exp, $cf);
        }
        $f3 = new \Wwtg99\StructureFile\FileType\CommonFile('out.xlsx');
        $tests = ['file1'=>'file1.xlsx', 'file2.csv'=>'file2.csv', ''=>'out.xlsx'];
        foreach ($tests as $test => $exp) {
            $cf = $f3->formatFilename($test);
            $this->assertEquals($exp, $cf);
        }
    }

    public function testSection()
    {
        $name = 'name1';
        $data = [['f1'=>'v1', 'f2'=>'v2'], ['f1'=>'v3', 'f2'=>'v4']];
        $head = [['title'=>'t1', 'field'=>'f1', 'type'=>'string'], ['title'=>'t2', 'field'=>'f2', 'type'=>'int']];
        $s = new \Wwtg99\StructureFile\SectionFile\Section(
            \Wwtg99\StructureFile\SectionFile\Section::KV_SECTION, $name, $data, $head, []
        );
        $this->assertEquals($s->getDel(), "\t");
        $this->assertEquals($s->getName(), $name);
        $this->assertEquals($s->getType(), \Wwtg99\StructureFile\SectionFile\Section::KV_SECTION);
        $this->assertEquals($s->getNull(), '-');
        $this->assertEquals($s->getPostfix(), '');
        $this->assertEquals($s->getPrefix(), '');
        $this->assertTrue($s->getShowName());
        $this->assertTrue($s->getShowHead());
        $this->assertEquals($s->getSkip(), []);
        $this->assertEquals($s->getHead(), $head);
        $this->assertEquals($s->getData(), $data);
        $rule = ['null'=>'*', 'skip'=>['f1'], 'del'=>'|', 'prefix'=>'#', 'postfix'=>'$', 'showName'=>false, 'showHead'=>false];
        $s = new \Wwtg99\StructureFile\SectionFile\Section(
            \Wwtg99\StructureFile\SectionFile\Section::KV_SECTION, $name, $data, $head, $rule
        );
        $this->assertEquals($s->getDel(), "|");
        $this->assertEquals($s->getName(), $name);
        $this->assertEquals($s->getType(), \Wwtg99\StructureFile\SectionFile\Section::KV_SECTION);
        $this->assertEquals($s->getNull(), '*');
        $this->assertEquals($s->getPostfix(), '$');
        $this->assertEquals($s->getPrefix(), '#');
        $this->assertFalse($s->getShowName());
        $this->assertFalse($s->getShowHead());
        $this->assertEquals($s->getSkip(), ['f1']);
        $this->assertEquals($s->getHead(), $head);
        $this->assertEquals($s->getData(), $data);
    }

    public function testSectionFile()
    {
        $name1 = 'name1';
        $data1 = ['f1'=>'v1', 'f2'=>'v2'];
        $head1 = [];
        $name2 = 'name2';
        $data2 = [['f1'=>'v1', 'f2'=>'v2'], ['f1'=>'v3', 'f2'=>'v4']];
        $head2 = [['title'=>'t1', 'field'=>'f1', 'type'=>'string'], ['title'=>'t2', 'field'=>'f2', 'type'=>'int']];
        $rule = ['null'=>'*', 'skip'=>['f1'], 'del'=>'|', 'prefix'=>'#', 'postfix'=>'$', 'showName'=>false, 'showHead'=>false];
        $s1 = new \Wwtg99\StructureFile\SectionFile\Section(
            \Wwtg99\StructureFile\SectionFile\Section::KV_SECTION, $name1, $data1, $head1, $rule
        );
        $s2 = new \Wwtg99\StructureFile\SectionFile\Section(
            \Wwtg99\StructureFile\SectionFile\Section::TSV_SECTION, $name2, $data2, $head2, $rule
        );
        $sf1 = new \Wwtg99\StructureFile\SectionFile\SectionFile([$s1, $s2]);
        $sf2 = new \Wwtg99\StructureFile\SectionFile\SectionFile();
        $sf2->addSections([$s1, $s2]);
        $sf3 = new \Wwtg99\StructureFile\SectionFile\SectionFile();
        $sf3->addSection($s1)->addSection($s2);
        $this->assertEquals($sf1, $sf2);
        $this->assertEquals($sf2, $sf3);
        $this->assertEquals(2, $sf2->getCount());
        $this->assertEquals(1, $sf1->getIndexByName($name2));
        $this->assertEquals(-1, $sf1->getIndexByName('no'));
        $this->assertEquals($s2, $sf1->getSectionByName($name2));
        $this->assertEquals(null, $sf1->getSectionByName('no'));
    }

    public function testTxtFile()
    {
        $name1 = 'name1';
        $data1 = ['f1'=>'v1', 'f2'=>'v2'];
        $head1 = [];
        $name2 = 'name2';
        $data2 = [['f1'=>'v1', 'f2'=>'v2'], ['f1'=>'v3', 'f2'=>'v4']];
        $head2 = [['title'=>'t1', 'field'=>'f1', 'type'=>'string'], ['title'=>'t2', 'field'=>'f2', 'type'=>'int']];
        $rule = [];
        $s1 = new \Wwtg99\StructureFile\SectionFile\Section(
            \Wwtg99\StructureFile\SectionFile\Section::KV_SECTION, $name1, $data1, $head1, $rule
        );
        $s2 = new \Wwtg99\StructureFile\SectionFile\Section(
            \Wwtg99\StructureFile\SectionFile\Section::TSV_SECTION, $name2, $data2, $head2, $rule
        );
        $sf1 = new \Wwtg99\StructureFile\SectionFile\SectionFile([$s1, $s2]);
        $txt1 = \Wwtg99\StructureFile\FileType\TxtFile::createFromSection($sf1);
        $expfile1 = "[name1]\nf1\tv1\nf2\tv2\n\n[name2]\nt1\tt2\nv1\tv2\nv3\tv4\n";
        $txt2 = new \Wwtg99\StructureFile\FileType\TxtFile('', $expfile1);
        $this->assertEquals($txt2->getContent(), $txt1->getContent());

        $data1 = ['f1'=>'v1', 'f2'=>'v2'];
        $head1 = [['title'=>'t1', 'field'=>'f1'], ['title'=>'f2', 'field'=>'f2']];

        $data2 = [['f1'=>'v1', 'f2'=>'v2'], ['f1'=>null, 'f2'=>'v4']];
        $head2 = [['title'=>'t1', 'field'=>'f1', 'type'=>'string'], ['title'=>'t2', 'field'=>'f2', 'type'=>'int']];
        $rule = ['null'=>'*', 'skip'=>['f2'], 'del'=>'|', 'prefix'=>'#', 'postfix'=>'$', 'showName'=>true, 'showHead'=>true];
        $s1 = new \Wwtg99\StructureFile\SectionFile\Section(
            \Wwtg99\StructureFile\SectionFile\Section::KV_SECTION, '', $data1, $head1, $rule
        );
        $s2 = new \Wwtg99\StructureFile\SectionFile\Section(
            \Wwtg99\StructureFile\SectionFile\Section::TSV_SECTION, '', $data2, $head2, $rule
        );
        $sf1 = new \Wwtg99\StructureFile\SectionFile\SectionFile([$s1, $s2]);
        $txt1 = \Wwtg99\StructureFile\FileType\TxtFile::createFromSection($sf1);
        $expfile1 = "[Section 1]\n#t1|v1$\n\n[Section 2]\nt1\n#v1$\n#*$\n";
        $txt2 = new \Wwtg99\StructureFile\FileType\TxtFile('', $expfile1);
        $this->assertEquals($txt2->getContent(), $txt1->getContent());
    }

    public function testExcelFile()
    {
        $data1 = ['f1'=>'v1', 'f2'=>'v2'];
        $head1 = [['title'=>'t1', 'field'=>'f1'], ['title'=>'f2', 'field'=>'f2']];

        $data2 = [['f1'=>'v1', 'f2'=>'v2'], ['f1'=>null, 'f2'=>'v4']];
        $head2 = [['title'=>'t1', 'field'=>'f1', 'type'=>'string'], ['title'=>'t2', 'field'=>'f2', 'type'=>'int']];
        $rule = ['null'=>'*', 'skip'=>['f2'], 'del'=>'|', 'prefix'=>'#', 'postfix'=>'$', 'showName'=>true, 'showHead'=>true];
        $s1 = new \Wwtg99\StructureFile\SectionFile\Section(
            \Wwtg99\StructureFile\SectionFile\Section::KV_SECTION, '', $data1, $head1, $rule
        );
        $s2 = new \Wwtg99\StructureFile\SectionFile\Section(
            \Wwtg99\StructureFile\SectionFile\Section::TSV_SECTION, '', $data2, $head2, $rule
        );
        $sf1 = new \Wwtg99\StructureFile\SectionFile\SectionFile([$s1, $s2]);
        $excel1 = \Wwtg99\StructureFile\FileType\ExcelFile::createFromSection($sf1);
        $excel1->writeTo('out.xlsx');
        $data3 = [['a', 'b', 'c'], ['d', 'e', 'f'], ['g', 'h', 'i']];
        $data4 = [['a', 'b', 'c', ''], ['d', 'e', 'f', ''], ['g', 'h', 'i', '']];
        $excel2 = \Wwtg99\StructureFile\FileType\ExcelFile::createFromData($data3);
        $excel2->writeTo('out2.xlsx');
        $excel3 = new \Wwtg99\StructureFile\FileType\ExcelFile('out2.xlsx');
        $arr = $excel3->getArray();
        $this->assertEquals($data4, $arr);
    }
}
 