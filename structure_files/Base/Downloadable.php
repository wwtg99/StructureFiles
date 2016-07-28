<?php
/**
 * Created by PhpStorm.
 * User: wuwentao
 * Date: 2016/2/29
 * Time: 15:40
 */

namespace Wwtg99\StructureFile\Base;


interface Downloadable {

    /**
     * Download file in web browser.
     *
     * @param string $filename
     * @return void
     */
    public function download($filename = '');
}
