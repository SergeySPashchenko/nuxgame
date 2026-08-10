<?php

namespace App\Http\Middleware;

use App\Models\AccessLink;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gate for all Page A routes: only active, non-expired access links may proceed.
 *
 * Delegates validity to AccessLink::isValid() (single source of truth).
 * Invalid links respond with HTTP 410 Gone.
 */
class EnsureValidAccessLink
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $accessLink = $request->route('accessLink');

        if (! $accessLink instanceof AccessLink || ! $accessLink->isValid()) {
            abort(410, 'This access link is no longer valid.');
        }

        return $next($request);
    }
}
