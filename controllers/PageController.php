<?php
namespace Controllers;

use App\Exceptions\AppError;
use App\Exceptions\AppException;

class PageController extends BaseController
{

    public function testAction() 
    {
        return ['test'];
    }
}