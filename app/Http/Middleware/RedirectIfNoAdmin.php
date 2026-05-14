<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Repositories\Contracts\UsersRepositoryInterface;

class RedirectIfNoAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    private $users;

    public function __construct(UsersRepositoryInterface $users)
    {
        $this->users = $users;
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (empty($this->users->getByRoleId(1))) {
        	return redirect(route('adminreg'));
        }

        return $next($request);
    }
}
