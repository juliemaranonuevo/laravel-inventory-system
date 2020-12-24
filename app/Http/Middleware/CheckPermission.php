<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$permissions)
    {
        $userPermissions = Auth::user();
        
        foreach($permissions as $permission) {
            switch($permission) {
                case 'AO': //Can add office
                if($userPermissions->user_type == 1){ return $next($request); }
                break;

                case 'EO': //Can edit office
                if($userPermissions->user_type == 1){ return $next($request); }
                break;

                case 'VO': //Can view office
                if($userPermissions->user_type == 1){ return $next($request); }
                break;

                case 'AU': //Can add user
                if($userPermissions->user_type == 1){ return $next($request); }
                break;

                case 'EU': //Can edit user
                if($userPermissions->user_type == 1){ return $next($request); }
                break;

                case 'VU': //Can view user
                if($userPermissions->user_type == 1){ return $next($request); }
                break;

                case 'AI': //Can add item
                if($userPermissions->user_type == 0){ return $next($request); }
                break;

                case 'EI': //Can edit item
                if($userPermissions->user_type == 0){ return $next($request); }
                break;

                case 'VI': //Can view item
                if($userPermissions->user_type == 0 || $userPermissions->user_type == 1){ return $next($request); }
                break;

                case 'ES': //Can edit sticker
                if($userPermissions->user_type == 0){ return $next($request); }
                break;

                case 'VS': //Can view sticker
                if($userPermissions->user_type == 0 || $userPermissions->user_type == 1){ return $next($request); }
                break;
                
                case 'AVT': //Can add and view item transaction
                if($userPermissions->user_type == 0){ return $next($request); }
                break;

                case 'GR': //Can generate reports
                if($userPermissions->user_type == 0 || $userPermissions->user_type == 1){ return $next($request); }
                break;
            }
        }
        return redirect('/');
    }
}
