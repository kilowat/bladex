<?php
namespace App\Controllers\Public;

use Controllers\BaseController;

class PublicController extends BaseController
{

    public function testAction() 
    {
        return ['test'];
    }
}