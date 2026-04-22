<?php

use Modules\Quizzes\Controllers\AdminQuizzes;
use Modules\Quizzes\Controllers\Quizzes;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Admin Routes for Quizzes ***/
$routes->group('dt_admin', [
    'namespace' => 'Modules\Quizzes\Controllers',
    'filter' => 'admin_filter'
], static function ($routes) {
    // Admin routes for managing quizzes
    $routes->match(['GET', 'POST'], 'quizzes', [AdminQuizzes::class, 'index']);
    $routes->match(['GET', 'POST'], 'quizzes/create', [AdminQuizzes::class, 'create']);
    $routes->post('quizzes/store', [AdminQuizzes::class, 'create']); // backward compat
    $routes->get('quizzes/store', function() {
        return redirect()->to('/dt_admin/quizzes/create');
    });
    $routes->match(['GET', 'POST'], 'quizzes/edit/(:num)', [AdminQuizzes::class, 'edit/$1']);
    $routes->post('quizzes/update/(:num)', [AdminQuizzes::class, 'edit/$1']);
    $routes->get('quizzes/view/(:num)', [AdminQuizzes::class, 'view/$1']);
    $routes->get('quizzes/questions/(:num)', [AdminQuizzes::class, 'questions/$1']);
    $routes->get('quizzes/import', [AdminQuizzes::class, 'importQuiz']);
    $routes->post('quizzes/import', [AdminQuizzes::class, 'importQuiz']);
    $routes->get('quizzes/attempts/(:num)', [AdminQuizzes::class, 'attempts/$1']);
    $routes->get('quizzes/attempts', [AdminQuizzes::class, 'attempts']);
    $routes->get('quizzes/view-attempt/(:num)', [AdminQuizzes::class, 'viewAttempt/$1']);
    $routes->post('quizzes/delete', [AdminQuizzes::class, 'delete']);
    $routes->post('quizzes/delete/(:num)', [AdminQuizzes::class, 'delete/$1']);
    $routes->get('quizzes/export/(:num)', [AdminQuizzes::class, 'exportQuiz/$1']);
    $routes->get('quizzes/analytics', [AdminQuizzes::class, 'analytics']);
    $routes->get('quizzes/statistics', [AdminQuizzes::class, 'getStatistics']);
    $routes->post('quizzes/toggle-status/(:num)', [AdminQuizzes::class, 'toggleStatus/$1']);
});

/*** Site Routes for Quizzes ***/
$routes->group('/', [
    'namespace' => 'Modules\Quizzes\Controllers'
], static function ($routes) {
    // Public quiz routes
    $routes->get('quizzes', [Quizzes::class, 'index']);
    
    // Test route for debugging session data
    $routes->get('quizzes/testSession', [Quizzes::class, 'testSession']);
    
    // Routes requiring authentication
    $routes->group('', ['filter' => 'site_filter'], static function ($routes) {
        // Quiz taking
        $routes->get('quizzes/take/(:num)', [Quizzes::class, 'take/$1']);
        $routes->get('quizzes/start/(:num)', [Quizzes::class, 'take/$1']); // Redirect GET requests to take page
        $routes->post('quizzes/start/(:num)', [Quizzes::class, 'startAttempt/$1']);
        $routes->get('quizzes/continue/(:num)', [Quizzes::class, 'continueAttempt/$1']);
        $routes->get('quizzes/submit/(:num)', [Quizzes::class, 'redirectToQuiz/$1']); // Redirect GET requests to proper workflow
        $routes->post('quizzes/submit-attempt/(:num)', [Quizzes::class, 'submitAnswers/$1']); // Submit with attempt ID
        $routes->get('quizzes/results/(:num)', [Quizzes::class, 'results/$1']);
        
        // Embedded quiz routes for course integration
        $routes->get('quizzes/start-embedded/(:num)', [Quizzes::class, 'redirectToEmbeddedQuiz/$1']); // Redirect GET requests to proper workflow
        $routes->post('quizzes/start-embedded/(:num)', 'Quizzes::startEmbedded/$1');
        $routes->post('quizzes/save-answer-embedded/(:num)', 'Quizzes::saveAnswer/$1');
        $routes->post('quizzes/submit-embedded/(:num)', [Quizzes::class, 'submitEmbedded/$1']);
        
        // Test route for debugging
        $routes->get('quizzes/test-submit/(:num)', [Quizzes::class, 'submitEmbedded/$1']);
        
        // Additional routes for JavaScript compatibility
        $routes->post('quizzes/save-answer/(:num)', 'Quizzes::saveAnswer/$1');
        
        // User quiz history
        $routes->get('quizzes/my-attempts', [Quizzes::class, 'myAttempts']);
        $routes->get('quizzes/attempts/(:num)', [Quizzes::class, 'viewAttempt/$1']);
    });
});