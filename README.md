
---

# CI Rest Remote
Remote WordPress Control with API-Driven RESTful command syntax.
Here's the README in Markdown format, ready to be copy-pasted:

## Description
**CI Site Manager** is a powerful and versatile WordPress Must-Use (MU) plugin that allows comprehensive site management via REST API endpoints and WP-CLI commands. With CI Site Manager, you can perform actions such as plugin and theme management, user management, maintenance mode toggling, site options management, and even database-wide search-replace operations. Perfect for multisite administrators or developers managing multiple sites, CI Site Manager provides a secure API key-based authentication system and a simple UI for managing the API key.

## Features
- **Plugin Management**: List, activate, deactivate, install, and delete plugins.
- **Theme Management**: List, activate, deactivate, install, and delete themes.
- **User Management**: List users, add new users, and delete users.
- **Maintenance Mode**: Enable, disable, and check the status of maintenance mode.
- **Site Option Management**: Get and update WordPress options programmatically.
- **Search-Replace**: Perform find-and-replace operations across the WordPress database.
- **WP-CLI Integration**: Access all registered REST API functionality through WP-CLI commands for server-side control.

## Requirements
- **WordPress**: 5.0 or above
- **PHP**: 7.2 or above (PHP 8 recommended)
- **WP-CLI**: Optional but recommended for command-line management

## Installation
As this is an MU (Must-Use) plugin, it is automatically activated when placed in the correct directory:

1. **Download or clone** this repository into your WordPress `wp-content/mu-plugins` folder.
2. Ensure the main plugin file is named `ci-site-manager.php`.
3. **No activation is required** as MU plugins are always active.

## REST API Endpoints

The plugin registers the following REST API endpoints under the namespace `ci-site-manager/v1`. All endpoints require a valid API key to be passed in the headers for authorization.

**Note:** Use the following base URL for all endpoints:
```
https://yourwebsite.com/wp-json/ci-site-manager/v1
```

### Plugin Management
- `GET /plugin/list`: List all installed plugins.
- `POST /plugin/activate/{slug}`: Activate a plugin by slug.
- `POST /plugin/deactivate/{slug}`: Deactivate a plugin by slug.
- `POST /plugin/install`: Install a plugin using a manifest object.
- `DELETE /plugin/delete/{slug}`: Delete a plugin by slug.

### Theme Management
- `GET /theme/list`: List all installed themes.
- `POST /theme/activate/{slug}`: Activate a theme by slug.
- `POST /theme/deactivate/{slug}`: Deactivate a theme by slug.
- `POST /theme/install`: Install a theme using a manifest object.
- `DELETE /theme/delete/{slug}`: Delete a theme by slug.

### User Management
- `GET /user/list`: List all users.
- `POST /user/add`: Add a new user with username, email, and password.
- `DELETE /user/delete/{id}`: Delete a user by user ID.

### Maintenance Mode
- `POST /maintenance/enable`: Enable maintenance mode.
- `POST /maintenance/disable`: Disable maintenance mode.
- `GET /maintenance/status`: Check if maintenance mode is active.

### Site Option Management
- `GET /option/get/{option_name}`: Retrieve the value of a WordPress option.
- `POST /option/update/{option_name}`: Update the value of a WordPress option.

### Search-Replace
- `POST /search-replace`: Perform a find-and-replace operation across the WordPress database.

## WP-CLI Commands

CI Site Manager provides WP-CLI commands that mirror the REST API endpoints, making it possible to manage plugins, themes, users, and more directly from the command line. Run `wp ci:<command>` for each of the following actions:

### Plugin Management
- `wp ci:list-plugins`: List all installed plugins.
- `wp ci:activate-plugin <slug>`: Activate a plugin by slug.
- `wp ci:deactivate-plugin <slug>`: Deactivate a plugin by slug.
- `wp ci:install-plugin <manifest>`: Install a plugin from a manifest.
- `wp ci:delete-plugin <slug>`: Delete a plugin by slug.

### Theme Management
- `wp ci:list-themes`: List all installed themes.
- `wp ci:activate-theme <slug>`: Activate a theme by slug.
- `wp ci:deactivate-theme <slug>`: Deactivate a theme by slug.
- `wp ci:install-theme <manifest>`: Install a theme from a manifest.
- `wp ci:delete-theme <slug>`: Delete a theme by slug.

### User Management
- `wp ci:list-users`: List all users.
- `wp ci:add-user <username> <email> <password>`: Add a new user.
- `wp ci:delete-user <id>`: Delete a user by ID.

### Maintenance Mode
- `wp ci:enable-maintenance`: Enable maintenance mode.
- `wp ci:disable-maintenance`: Disable maintenance mode.
- `wp ci:maintenance-status`: Check if maintenance mode is active.

### Site Option Management
- `wp ci:get-option <option_name>`: Retrieve a WordPress option.
- `wp ci:update-option <option_name> <value>`: Update a WordPress option.

### Search-Replace
- `wp ci:search-replace <search> <replace>`: Perform a find-and-replace operation in the database.

## Admin Settings Page

CI Site Manager includes a settings page in the WordPress admin for managing the API key:

1. **API Key**: The current API key is displayed in a read-only text box.
2. **Regenerate**: Click to generate a new API key, which is saved asynchronously via AJAX.
3. **Copy**: Copy the API key to your clipboard for easy sharing and access.

## License
This plugin is open-source and available under the MIT License.

## Contributions
Contributions and improvements are welcome! Feel free to fork this repository and submit pull requests to enhance functionality or fix bugs.

---

Enjoy using **CI Site Manager** to streamline and simplify your WordPress management tasks!