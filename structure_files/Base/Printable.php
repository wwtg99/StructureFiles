<?php
/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2016/2/29
 * Time: 15:41
 */

namespace StructureFile\Base;


interface Printable {

    /**
     * Print file in web browser.
     *
     * @return void
     */
    public function printContent();
}
