<?php

namespace App\Daran\Http\Middleware;

use Closure;
use App\Daran\Models\Redirection;

class RedirectRequests
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $from = $request->path();
        $parti = explode('/',$request->route()->uri);
        $from_parti = explode('/',$request->path());
        $transform = array();

        $redirect = Redirection::findByOrigin($request->route()->uri);

        // if (! $redirect && $request->getQueryString()) {
        //     $path = $request->path().'?'.$request->getQueryString();
        //     $redirect = Redirection::findByOrigin($path);
        // }

        if ($redirect) {
            $to_uri = $redirect->to_uri;

            for($i=0;$i<count($parti);$i++){
                if($parti[$i][0] == '{'){
                    $transform[$parti[$i]] = $from_parti[$i];
                }
            }

            foreach($transform as $key=>$value){
                $to_uri = str_replace($key,$value,$to_uri);
            }

            return redirect($to_uri, $redirect->code);
        }

        return $next($request);
    }
}
