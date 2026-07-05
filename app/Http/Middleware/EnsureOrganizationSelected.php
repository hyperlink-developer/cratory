<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganizationSelected
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // If user has no organizations, force onboarding
        if (!$user->hasOrganizations()) {
            if (!$request->routeIs('onboarding.*')) {
                return redirect()->route('onboarding.wizard');
            }
        }

        // If user has orgs but none selected, pick the default
        if ($user->hasOrganizations() && !$user->current_organization_id) {
            $defaultOrg = $user->organizations()
                ->wherePivot('is_default_org', true)
                ->first()
                ?? $user->organizations()->first();

            if ($defaultOrg) {
                $user->switchOrganization($defaultOrg->id);
            }
        }

        return $next($request);
    }
}
