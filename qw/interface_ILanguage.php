<?php
/**
 * Created by PhpStorm.
 * User: xpy
 * Date: 10.4.15
 * Time: 0:55
 */

interface ILanguage {
    public function languageGetPack();
    public function languageGetMetaPack();
    public function languageSetAllPackages();
    public function languageGetAllPackages();
}