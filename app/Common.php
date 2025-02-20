<?php

use CodeIgniter\Config\View;
use Config\Services;

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter4.github.io/CodeIgniter4/
 */

if (!function_exists('View')) {
    function View(string $name, array $data = [], array $options = []): string
    {
        $renderer = \Config\Services::renderer();
        $saveData = config('View')->saveData;

        // Update saveData option if provided
        $saveData = $options['saveData'] ?? $saveData;

        $module = currentModule(); // Ensure currentModule() returns the current module name

        // Determine viewType based on the presence of 'site' in the view name
        $viewType = str_contains($name, 'site/') ? 'Site' : 'Admin';

        // Adjust view path, removing 'site/' if present
        $name = $viewType === 'Site' ? str_replace('site/', '', $name) : $name;

        // Construct the full path
        $fullPath = "\\Modules\\{$module}\\Views\\{$viewType}\\{$name}";

        return $renderer->setData($data, 'raw')
            ->render($fullPath, $options, $saveData);
    }
}




if (! function_exists('MainView')) {
    /**
     * Grabs the current RendererInterface-compatible class
     * and tells it to render the specified view. Simply provides
     * a convenience method that can be used in Controllers,
     * libraries, and routed closures.
     *
     * NOTE: Does not provide any escaping of the data, so that must
     * all be handled manually by the developer.
     *
     * @param array $options Options for saveData or third-party extensions.
     */
    function MainView(string $name, array $data = [], array $options = []): string
    {
        /** @var CodeIgniter\View\View $renderer */
        $renderer = Services::renderer();

        /** @var \CodeIgniter\Config\View $config */
        $config = config(View::class);
        $saveData = $config->saveData;

        if (array_key_exists('saveData', $options)) {
            $saveData = (bool) $options['saveData'];
            unset($options['saveData']);
        }

        // Log the view name and options for debugging
        log_message('debug', 'Rendering view: ' . $name);
        log_message('debug', 'View options: ' . print_r($options, true));

        try {
            return $renderer->setData($data, 'raw')
                ->render($name, $options, $saveData);
        } catch (\Exception $e) {
            log_message('error', 'Error rendering view: ' . $e->getMessage());
            throw $e;
        }
    }

}
#@todo fix switch lang
#@todo add login register flow and make them work correctly
#@todo add ability to click on lang save it to the lang file

