<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Jcove\Restful\Utils;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Jcove\Restful\Result;
use PHPUnit\Util\RegularExpression;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
//        if($exception instanceof ModelNotFoundException){
//            return respond(['message'=>$exception->getMessage()],404);
//        }
        if($exception instanceof AuthenticationException){
            return $this->unauthenticated($request,$exception);
        }
        if($exception instanceof AuthorizationException){
            return $this->fail(trans('message.access_denied'),403);
        }
        if($exception instanceof GoodsException){
            return $this->fail($exception->getMessage(),501);
        }
        if($exception instanceof  UnauthorizedException){
            if($exception->getMessage()=='User is not logged in.'){
                return $this->fail(trans('message.not_login'),401);
            }

            if($exception->getMessage()=='User does not have the right roles.'){
                return $this->fail(trans('message.access_denied'),403);
            }
            if($exception->getMessage()=='User does not have the right permissions.'){
                return $this->fail(trans('message.access_denied'),403);
            }
        }
        return parent::render($request, $exception);
    }

    protected function convertExceptionToArray(Exception $e)
    {
        return config('app.debug') ? [
            'message' => $e->getMessage(),
            'exception' => get_class($e),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => collect($e->getTrace())->map(function ($trace) {
                return Arr::except($trace, ['args']);
            })->all(),
        ] : [
            'message' => $e->getMessage(),
            'code'=>$e->getCode()
        ];
    }

    protected function fail($message,$status=404){
        if(request()->acceptsJson()){
            return response()->json(['message'=>$message],$status);
        }
        $view               =   'error.'.$status;
        if(Utils::isMobileBrowser()){
            $view           =   config('restful.mobile_browser_prefix').'.'.$view;
        }else{
            $view           =   config('restful.pc_browser_prefix').'.'.$view;
        }
        return response()->view($view, ['message'=>$message],$status);
    }


}
