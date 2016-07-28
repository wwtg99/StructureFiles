<?php
/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2016/2/29
 * Time: 16:05
 */

namespace Wwtg99\StructureFile\SectionFile;


class Section {

    const TSV_SECTION = 1;
    const KV_SECTION = 2;
    const RAW_SECTION = 3;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     * [[field=>"", title=>"", type=>""], ...]
     */
    protected $head;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var int: TSV_SECTION or KV_SECTION or RAW_SECTION
     */
    protected $type;

    /**
     * @var array
     *
     * rules:
     * showHead: bool, default true
     * showName: bool, default true
     * null: string, default '-'
     * skip: array, default []
     * prefix: string, default ''
     * postfix: string, default ''
     * del: string, default '\t'
     */
    protected $rules = [
        'showHead'=>true,
        'showName'=>true,
        'null'=>'-',
        'skip'=>[],
        'prefix'=>'',
        'postfix'=>'',
        'del'=>"\t"
    ];

    /**
     * @param int $type
     * @param string $name
     * @param array $data
     * @param array $head
     * @param array $rule
     */
    function __construct($type, $name, $data, $head = [], $rule = [])
    {
        $this->type = $type;
        $this->name = $name;
        $this->data = $data;
        $this->head = $head;
        $this->rules = array_merge($this->rules, $rule);
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getHead()
    {
        return $this->head;
    }

    /**
     * @param array $head
     */
    public function setHead($head)
    {
        $this->head = $head;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getRule($name)
    {
        if (isset($this->rules[$name])) {
            return $this->rules[$name];
        }
        return null;
    }

    /**
     * @return bool
     */
    public function getShowHead()
    {
        return $this->getRule('showHead');
    }

    /**
     * @return bool
     */
    public function getShowName()
    {
        return $this->getRule('showName');
    }

    /**
     * @return string
     */
    public function getNull()
    {
        return $this->getRule('null');
    }

    /**
     * @return array
     */
    public function getSkip()
    {
        return $this->getRule('skip');
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->getRule('prefix');
    }

    /**
     * @return string
     */
    public function getPostfix()
    {
        return $this->getRule('postfix');
    }

    /**
     * @return string
     */
    public function getDel()
    {
        return $this->getRule('del');
    }

    /**
     * @param string $type
     * @return int
     */
    public static function getTypeCode($type)
    {
        switch (strtolower($type)) {
            case 'tsv': return Section::TSV_SECTION;
            case 'kv': return Section::KV_SECTION;
            default: return Section::RAW_SECTION;
        }
    }
}
