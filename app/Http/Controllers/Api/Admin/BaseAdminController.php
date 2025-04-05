<?php

namespace App\Http\Controllers\Api\Admin;

use App\Factories\ApiResponseFactory;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseAdminController extends Controller
{
    /**
     * Override callAction to enforce superadmin check for every method.
     *
     * @param  string  $method
     * @param  array   $parameters
     */
    public function callAction($method, $parameters): Response
    {
        $user = $this->getUser();
        if ($user->getType()!== 'superadmin') {
            return ApiResponseFactory::error('Not Implemented', 501);
        }
        return parent::callAction($method, $parameters);
    }
}
