<?php

namespace App\Http\Controllers\Api;

use App\Http\Traits\Ajax;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EllipseSynergie\ApiResponse\Laravel\Response;

class BaseController extends Controller
{
    use Ajax;

    protected $response;
    protected $request;
    protected $requiredParamName;

    public function __construct(Response $response, Request $request)
    {
        parent::__construct();

        $this->response = $response;
        $this->request = $request;
    }

    public function method($method = null)
    {
        if (method_exists($this, $method)) {
            return $this->call(function() use ($method) {
                return $this->$method();
            });
        }

        return $this->unprocessable("No method named: '$method'");
    }

    protected function call($closure)
    {
        try {
            return $closure();
        } catch (ApiException $e) {
            return $this->unprocessable("Required parameter: '$this->requiredParamName' is not supplied");
        }
    }

    protected function unprocessable($message)
    {
        return $this->response->errorUnprocessable($message);
    }

    protected function requireParam($name)
    {
        if ($this->request->has($name)) {
            return $this->request->input($name);
        }

        $this->requiredParamName = $name;

        throw new ApiException;
    }
}