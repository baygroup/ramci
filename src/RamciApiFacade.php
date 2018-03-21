<?php
/**
 * Created by PhpStorm.
 * User: michael.grunewalder
 * Date: 15/03/2018
 * Time: 5:06 PM
 */

namespace Baygroup\Ramci;

use Illuminate\Support\Facades\Facade;


class RamciApiFacade extends Facade
{
    protected static function getFacadeAccessor() { return 'RamciApi'; }
}