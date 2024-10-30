<?php
/*
Plugin Name: CI Site Manager
Description: Manage plugins, themes, users, site options, maintenance mode, and perform search-replace operations via REST API and WP-CLI.
Version: 1.0.0
Author: Chris
*/

if (!defined('ABSPATH')) {
    exit;
}

class CISiteManager
{
    private $namespace = 'ci-site-manager/v1';

    public function __construct()
    {
        
        // Add admin options page
        add_action('admin_menu', [$this, 'add_settings_page']);
        
        // AJAX for regenerating the API key
        add_action('wp_ajax_ci_generate_api_key', [$this, 'ajax_generate_api_key']);

        // Register routes and CLI commands
        add_action('rest_api_init', [$this, 'register_routes']);
        if (defined('WP_CLI') && WP_CLI) {
            $this->register_wp_cli_commands();
        }
        
    }

    /**
     * Validate the API Key
     */
    public function validate_api_key()
    {
        $provided_key = $_SERVER['HTTP_X_CI_API_KEY'] ?? '';
        $stored_key = get_option('ci_site_manager_api_key');
        return hash_equals($stored_key, $provided_key);
    }

    /**
     * Add Settings Page to the WordPress Admin
     */
    public function add_settings_page()
    {
        add_options_page(
            'CI Site Manager',
            'CI Site Manager',
            'manage_options',
            'ci-site-manager',
            [$this, 'settings_page_content']
        );
    }

    /**
     * Display Settings Page Content
     */
    public function settings_page_content()
    {
        // Fetch existing API key or generate a new one if none exists
        $api_key = get_option('ci_site_manager_api_key', $this->generate_api_key());

        // Render the settings page HTML
        echo '<div class="wrap">';
        echo '<h1>CI Site Manager</h1>';
        echo '<table class="form-table">';
        echo '<tr valign="top"><th scope="row">API Key</th><td>';
        echo '<input type="text" id="ci_site_manager_api_key" value="' . esc_attr($api_key) . '" readonly="readonly" style="width: 400px;">';
        echo ' <button id="ci_generate_api_key" class="button"><span class="dashicons dashicons-image-rotate"></span> Regenerate</button>';
        echo ' <button id="ci_copy_api_key" class="button"><span class="dashicons dashicons-clipboard"></span> Copy</button>';
        echo '</td></tr>';
        echo '</table>';
        echo '</div>';

        // Include JavaScript for AJAX refresh and copy functionality
        ?>
        <script>
        document.getElementById('ci_generate_api_key').addEventListener('click', function() {
            fetch('<?php echo admin_url('admin-ajax.php?action=ci_generate_api_key'); ?>', {
                method: 'POST',
                headers: {
                    'X-WP-Nonce': '<?php echo wp_create_nonce('ci_generate_api_key'); ?>'
                }
            }).then(response => response.json()).then(data => {
                document.getElementById('ci_site_manager_api_key').value = data.api_key;
            });
        });

        document.getElementById('ci_copy_api_key').addEventListener('click', function() {
            const apiKeyField = document.getElementById('ci_site_manager_api_key');
            apiKeyField.select();
            document.execCommand('copy');
            alert('API Key copied to clipboard');
        });
        </script>
        <?php
    }

    /**
     * Generate a new API key and save it to the database
     */
    private function generate_api_key()
    {
        $api_key = bin2hex(random_bytes(16));
        update_option('ci_site_manager_api_key', $api_key);
        return $api_key;
    }

    /**
     * Handle AJAX request for regenerating API key
     */
    public function ajax_generate_api_key()
    {
        check_ajax_referer('ci_generate_api_key');
        $new_key = $this->generate_api_key();
        wp_send_json_success(['api_key' => $new_key]);
    }

    /**
     * Register all REST API routes
     */
    public function register_routes()
    {
        $endpoints = [
            // Plugin Management
            ['route' => '/plugin/list', 'methods' => 'GET', 'callback' => [$this, 'list_plugins']],
            ['route' => '/plugin/activate/(?P<slug>[a-zA-Z0-9-]+)', 'methods' => 'POST', 'callback' => [$this, 'activate_plugin']],
            ['route' => '/plugin/deactivate/(?P<slug>[a-zA-Z0-9-]+)', 'methods' => 'POST', 'callback' => [$this, 'deactivate_plugin']],

            // Theme Management
            ['route' => '/theme/list', 'methods' => 'GET', 'callback' => [$this, 'list_themes']],
            ['route' => '/theme/activate/(?P<slug>[a-zA-Z0-9-]+)', 'methods' => 'POST', 'callback' => [$this, 'activate_theme']],
            ['route' => '/theme/deactivate/(?P<slug>[a-zA-Z0-9-]+)', 'methods' => 'POST', 'callback' => [$this, 'deactivate_theme']],

            // User Management
            ['route' => '/user/list', 'methods' => 'GET', 'callback' => [$this, 'list_users']],
            ['route' => '/user/add', 'methods' => 'POST', 'callback' => [$this, 'add_user']],
            ['route' => '/user/delete/(?P<id>\d+)', 'methods' => 'DELETE', 'callback' => [$this, 'delete_user']],

            // Maintenance Mode Management
            ['route' => '/maintenance/enable', 'methods' => 'POST', 'callback' => [$this, 'enable_maintenance_mode']],
            ['route' => '/maintenance/disable', 'methods' => 'POST', 'callback' => [$this, 'disable_maintenance_mode']],
            ['route' => '/maintenance/status', 'methods' => 'GET', 'callback' => [$this, 'check_maintenance_status']],

            // Site Option Management
            ['route' => '/option/get/(?P<option_name>[a-zA-Z0-9-_]+)', 'methods' => 'GET', 'callback' => [$this, 'get_option']],
            ['route' => '/option/update/(?P<option_name>[a-zA-Z0-9-_]+)', 'methods' => 'POST', 'callback' => [$this, 'update_option']],

            // Find & Replace
            ['route' => '/search-replace', 'methods' => 'POST', 'callback' => [$this, 'search_replace']],
        ];

        foreach ($endpoints as $endpoint) {
            register_rest_route($this->namespace, $endpoint['route'], [
                'methods' => $endpoint['methods'],
                'callback' => $endpoint['callback'],
                'permission_callback' => [$this, 'validate_api_key']
            ]);
        }
    }

    /**
     * Validate the API Key
     */
    public function validate_api_key()
    {
        $provided_key = $_SERVER['HTTP_X_CI_API_KEY'] ?? '';
        $stored_key = get_option('ci_site_manager_api_key');
        return hash_equals($stored_key, $provided_key);
    }

    /**
     * Plugin Management
     */
    public function list_plugins() {
        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        return rest_ensure_response(get_plugins());
    }

    public function activate_plugin($request) {
        $slug = $request['slug'];
        activate_plugin($slug);
        return is_plugin_active($slug) ? rest_ensure_response(['status' => 'Plugin activated']) : new WP_Error('activation_failed', 'Failed to activate plugin');
    }

    public function deactivate_plugin($request) {
        $slug = $request['slug'];
        deactivate_plugins($slug);
        return !is_plugin_active($slug) ? rest_ensure_response(['status' => 'Plugin deactivated']) : new WP_Error('deactivation_failed', 'Failed to deactivate plugin');
    }

    /**
     * Theme Management
     */
    public function list_themes() {
        return rest_ensure_response(wp_get_themes());
    }

    public function activate_theme($request) {
        $slug = $request['slug'];
        switch_theme($slug);
        return rest_ensure_response(['status' => 'Theme activated']);
    }

    public function deactivate_theme($request) {
        // WordPress does not support deactivating themes directly
        return new WP_Error('not_supported', 'Deactivating a theme is not supported.');
    }

    /**
     * User Management
     */
    public function list_users() {
        $users = get_users();
        return rest_ensure_response($users);
    }

    public function add_user($request) {
        $username = sanitize_user($request->get_param('username'));
        $email = sanitize_email($request->get_param('email'));
        $password = $request->get_param('password');
        $user_id = wp_create_user($username, $password, $email);
        return is_wp_error($user_id) ? $user_id : rest_ensure_response(['status' => 'User created', 'user_id' => $user_id]);
    }

    public function delete_user($request) {
        $user_id = $request['id'];
        require_once ABSPATH . 'wp-admin/includes/user.php';
        return wp_delete_user($user_id) ? rest_ensure_response(['status' => 'User deleted']) : new WP_Error('deletion_failed', 'Failed to delete user');
    }

    /**
     * Maintenance Mode Management
     */
    public function enable_maintenance_mode() {
        file_put_contents(ABSPATH . '.maintenance', '<?php $upgrading = time();');
        return rest_ensure_response(['status' => 'Maintenance mode enabled']);
    }

    public function disable_maintenance_mode() {
        if (file_exists(ABSPATH . '.maintenance')) {
            unlink(ABSPATH . '.maintenance');
        }
        return rest_ensure_response(['status' => 'Maintenance mode disabled']);
    }

    public function check_maintenance_status() {
        return file_exists(ABSPATH . '.maintenance') ? rest_ensure_response(['status' => 'enabled']) : rest_ensure_response(['status' => 'disabled']);
    }

    /**
     * Site Option Management
     */
    public function get_option($request) {
        $option_name = $request['option_name'];
        return rest_ensure_response(get_option($option_name));
    }

    public function update_option($request) {
        $option_name = $request['option_name'];
        $value = $request->get_param('value');
        return update_option($option_name, $value) ? rest_ensure_response(['status' => 'Option updated']) : new WP_Error('update_failed', 'Failed to update option');
    }

    /**
     * Find & Replace (search-replace)
     */
    public function search_replace($request) {
        $search = $request->get_param('search');
        $replace = $request->get_param('replace');
        // Placeholder for actual search-replace logic across database tables
        return rest_ensure_response(['status' => 'Search-replace executed', 'search' => $search, 'replace' => $replace]);
    }

    /**
     * Register WP-CLI Commands (scaffolded, can expand similarly to REST endpoints)
     */
    public function register_wp_cli_commands() {
        WP_CLI::add_command('ci:list-plugins', [$this, 'wp_cli_list_plugins']);
        WP_CLI::add_command('ci:activate-plugin', [$this, 'wp_cli_activate_plugin']);
        // More WP-CLI commands...
    }

    /**
     * WP-CLI commands - example for listing plugins
     */
    public function wp_cli_list_plugins() {
        $plugins = $this->list_plugins()->data;
        foreach ($plugins as $slug => $plugin_data) {
            WP_CLI::log("{$plugin_data['Name']} ({$slug}) - Version: {$plugin_data['Version']}");
        }
    }
}

// Instantiate the class
new CISiteManager();
