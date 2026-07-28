<?php

declare(strict_types=1);

namespace App\Filters;

use App\Libraries\UserType;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Restricts access to instructor-only pages.
 */
class InstructorFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $auth = service('auth');
        $session = session();

        if (! $auth->loggedIn()) {
            $session->setFlashdata('error', 'يرجى تسجيل الدخول أولاً.');

            return redirect()->to('/login');
        }

        $user = $auth->user();

        if (! UserType::isInstructor($user)) {
            $session->setFlashdata('error', 'هذه الصفحة مخصصة للمحاضرين فقط.');

            return redirect()->to(site_url(UserType::getDefaultPath(UserType::normalize($user->user_type ?? null))));
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
