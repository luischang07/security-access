<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class HandleSessionExpiration
{
  /**
   * Handle an incoming request.
   */
  public function handle(Request $request, Closure $next): Response
  {
    $response = $next($request);

    if (!Auth::check() || session('remember_me') !== false || !($response instanceof Response)) {
      return $response;
    }

    $sessionCookieName = config('session.cookie');
    $cookies = $response->headers->getCookies();

    foreach ($cookies as $cookie) {
      if ($cookie->getName() === $sessionCookieName) {
        $response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie(
          $cookie->getName(),
          $cookie->getValue(),
          0, // Expiración al cerrar el navegador
          $cookie->getPath(),
          $cookie->getDomain(),
          $cookie->isSecure(),
          $cookie->isHttpOnly(),
          $cookie->isRaw(),
          $cookie->getSameSite()
        ));
        break;
      }
    }

    return $response;
  }
}
