<?php

namespace Sneek\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class XSSProtection
{
    public function handle(Request $request, Closure $next)
    {
        $input = $request->input();

        array_walk_recursive($input, function (&$input, $key) {
            if (starts_with($key, 'html_')) {
                return;
            }

            $input = e(strip_tags($input));
        });

        $request->merge($input);

        return $next($request);
    }
}