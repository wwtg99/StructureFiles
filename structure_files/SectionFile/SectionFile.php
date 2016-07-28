<?php
/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2016/2/29
 * Time: 16:07
 */

namespace Wwtg99\StructureFile\SectionFile;


class SectionFile implements \Iterator{

    /**
     * @var array
     */
    protected $sections = [];

    protected $cur;

    /**
     * @param array|Section $sections
     */
    function __construct($sections = [])
    {
        if (is_array($sections)) {
            $this->sections = $sections;
        } elseif ($sections instanceof Section) {
            array_push($this->sections, $sections);
        }
    }

    /**
     * @param Section $section
     * @return $this
     */
    public function addSection(Section $section)
    {
        array_push($this->sections, $section);
        return $this;
    }

    /**
     * @param array $sections
     * @return $this
     */
    public function addSections($sections)
    {
        foreach ($sections as $i => $s) {
            $this->addSection($s);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * @param int $index
     * @return null|Section
     */
    public function getSection($index)
    {
        if ($index >= 0 && $index < count($this->sections)) {
            return $this->sections[$index];
        }
        return null;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return count($this->sections);
    }

    /**
     * @return $this
     */
    public function clearSection()
    {
        $this->sections = [];
        return $this;
    }

    /**
     * @param string $name
     * @return int
     */
    public function getIndexByName($name)
    {
        foreach ($this->sections as $i => $sec) {
            if ($sec instanceof Section) {
                if ($sec->getName() == $name) {
                    return $i;
                }
            }
        }
        return -1;
    }

    /**
     * @param string $name
     * @return Section|null
     */
    public function getSectionByName($name)
    {
        foreach ($this->sections as $i => $sec) {
            if ($sec instanceof Section) {
                if ($sec->getName() == $name) {
                    return $sec;
                }
            }
        }
        return null;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return $this->sections[$this->cur];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        ++$this->cur;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->cur;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return isset($this->sections[$this->cur]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->cur = 0;
    }
}
