<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->nombres . ' ' . $request->user()->apellidos,
                    'email' => $request->user()->email,
                    'ci' => $request->user()->ci,
                    'telefono' => $request->user()->telefono,
                    'edad' => $request->user()->fecha_nacimiento ? $request->user()->fecha_nacimiento->age : null,
                    'roles' => $request->user()->roles->pluck('name')->toArray(),
                ] : null,
            ],
        ]);
    }
}
