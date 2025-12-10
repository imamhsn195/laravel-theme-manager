<?php

namespace ImamHasan\ThemeManager\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user) {
            throw new AccessDeniedHttpException('Authentication required.');
        }

        $roles = config('theme-manager.admin_roles', ['theme-admin']);
        $permissions = config('theme-manager.admin_permissions', ['manage theme manager']);

        if ($this->userHasRole($user, $roles) || $this->userHasPermission($user, $permissions)) {
            return $next($request);
        }

        throw new AccessDeniedHttpException('You do not have access to Theme Manager admin.');
    }

    protected function userHasRole($user, array $roles): bool
    {
        if (empty($roles) || ! method_exists($user, 'hasAnyRole')) {
            return false;
        }

        return $user->hasAnyRole($roles);
    }

    protected function userHasPermission($user, array $permissions): bool
    {
        if (empty($permissions) || ! method_exists($user, 'can')) {
            return false;
        }

        foreach ($permissions as $permission) {
            if ($user->can($permission)) {
                return true;
            }
        }

        return false;
    }
}
