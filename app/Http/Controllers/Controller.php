<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpKernel\Log\Logger;

/**
 * @OA\Info(
 *    title="Your super  ApplicationAPI",
 *    version="1.0.0",
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public static function success($msg, $data = [], $code = 200){

        $msgArray = array(
            'bool' => true,
            "message" => $msg
        );

        $returnArray = array_merge($msgArray, $data);
        // Logger::info('app.requests', ['type'=> 'success', 'request' => request()->all(), 'response' => $msg]);
        return response()->json( $returnArray, $code);
    }

    public static function failure($error, $data = [], $code = 422 ){

        $msgArray = array(
            'bool' => false,
            "message" => $error
        );

        $returnArray = array_merge($msgArray, $data);
        // Logger::info('app.requests', ['type'=> 'error', 'request' => request()->all(), 'response' => $error]);
        return response()->json( $returnArray, $code);
    }
}