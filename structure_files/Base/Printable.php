<?php
/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2016/2/29
 * Time: 15:41
 */

namespace Wwtg99\StructureFile\Base;


interface Printable {

    /**
     * Print file in web browser.
     *
     * @param string $filename
     * @return void
     */
    public function printContent($filename = '');
}
