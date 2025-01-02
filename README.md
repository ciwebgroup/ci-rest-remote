---

# CI Rest Remote
Remote WordPress Control with API-Driven RESTful command syntax.

## Description

**CI Rest Remote** is a powerful and versatile WordPress Must-Use (MU) plugin that allows comprehensive site management via REST API endpoints and WP-CLI commands. With CI Rest Remote, you can perform actions such as plugin and theme management, user management, maintenance mode toggling, site options management, post management, database backup and restore, and even database-wide search-replace operations. Perfect for multisite administrators or developers managing multiple sites, CI Rest Remote provides a secure API key-based authentication system and a simple UI for managing the API key.

## Features

- **Plugin Management**: List, activate, deactivate, update, install, and delete plugins.
- **Theme Management**: List, activate, deactivate, update, install, and delete themes.
- **User Management**: List users, add new users, and delete users.
- **Maintenance Mode**: Enable, disable, and check the status of maintenance mode.
- **Site Option Management**: Get and update WordPress options programmatically.
- **Post Management**: List, create, and delete posts.
- **Database Backup and Restore**: Backup and restore the WordPress database.
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
  - **Example**:
    ```bash
    curl -H "X-CI-API-KEY: your_api_key" https://yourwebsite.com/wp-json/ci-site-manager/v1/plugin/list
    ```

- `POST /plugin/activate/{slug}`: Activate a plugin by slug.
  - **Example**:
    ```bash
    curl -X POST -H "X-CI-API-KEY: your_api_key" https://yourwebsite.com/wp-json/ci-site-manager/v1/plugin/activate/hello-dolly
    ```

- `POST /plugin/deactivate/{slug}`: Deactivate a plugin by slug.
  - **Example**:
    ```bash
    curl -X POST -H "X-CI-API-KEY: your_api_key" https://yourwebsite.com/wp-json/ci-site-manager/v1/plugin/deactivate/hello-dolly
    ```

- `POST /plugin/update/{slug}`: Update a plugin by slug.
  - **Example**:
    ```bash
    curl -X POST -H "X-CI-API-KEY: your_api_key" https://yourwebsite.com/wp-json/ci-site-manager/v1/plugin/update/hello-dolly
    ```

- `POST /plugin/install`: Install a plugin using a manifest object.
  - **Example**:
    ```bash
    curl -X POST -H "X-CI-API-KEY: your_api_key" -d '{"slug": "plugin-slug", "source": "plugin-source-url"}' https://yourwebsite.com/wp-json/ci-site-manager/v1/plugin/install
    ```

- `DELETE /plugin/delete/{slug}`: Delete a plugin by slug.
  - **Example**:
    ```bash
    curl -X DELETE -H "X-CI-API-KEY: your_api_key" https://yourwebsite.com/wp-json/ci-site-manager/v1/plugin/delete/hello-dolly
    ```

### Theme Management

- `GET /theme/list`: List all installed themes.
  - **Example**:
    ```bash
    curl -H "X-CI-API-KEY: your_api_key" https://yourwebsite.com/wp-json/ci-site-manager/v1/theme/list
    ```

- `POST /theme/activate/{slug}`: Activate a theme by slug.
  - **Example**:
    ```bash
    curl -X POST -H "X-CI-API-KEY: your_api_key" https://yourwebsite.com/wp-json/ci-site-manager/v1/theme/activate/twentytwentyone
    ```

- `POST /theme/deactivate/{slug}`: Deactivate a theme by slug.
  - **Example**:
    ```bash
    curl -X POST -H "X-CI-API-KEY: your_api_key" https://yourwebsite.com/wp-json/ci-site-manager/v1/theme/deactivate/twentytwentyone
    ```

- `POST /theme/update/{slug}`: Update a theme by slug.
  - **Example**:
    ```bash
    curl -X POST -H "X-CI-API-KEY: your_api_key" https://yourwebsite.com/wp-json/ci-site-manager/v1/theme/update/twentytwentyone
    ```

- `POST /theme/install`: Install a theme using a manifest object.
  - **Example**:
    ```bash
    curl -X POST -H "X-CI-API-KEY: your_api_key" -d '{"slug": "theme-slug", "source": "theme-source-url"}' https://yourwebsite.com/wp-json/ci-site-manager/v1/theme/install
    ```

- `DELETE /theme/delete/{slug}`: Delete a theme by slug.
  - **Example**:
    ```bash
    curl -X DELETE -H "X-CI-API-KEY: your_api_key" https://yourwebsite.com/wp-json/ci-site-manager/v1/theme/delete/twentytwentyone
    ```

### User Management

- `GET /user/list`: List all users.
  - **Example**:
    ```bash
    curl -H "X-CI-API-KEY: your_api_key" https://yourwebsite.com/wp-json/ci-site-manager/v1/user/list
    ```

- `POST /user/add`: Add a new user with username, email, and password.
  - **Example**:
    ```bash
    curl -X POST -H "X-CI-API-KEY: your_api_key" -d '{"username": "newuser", "email": "newuser@example.com", "password": "password"}' https://yourwebsite.com/wp-json/ci-site-manager/v1/user/add
    ```

- `DELETE /user/delete/{id}`: Delete a user by user ID.
  - **Example**:
    ```bash
    curl -X DELETE -H "X-CI-API-KEY: your_api_key" https://yourwebsite.com/wp-json/ci-site-manager/v1/user/delete/1
    ```

### Maintenance Mode

- `POST /maintenance/enable`: Enable maintenance mode.
  - **Example**:
    ```bash
    curl -X POST -H "X-CI-API-KEY: your_api_key" https://yourwebsite.com/wp-json/ci-site-manager/v1/maintenance/enable
    ```

- `POST /maintenance/disable`: Disable maintenance mode.
  - **Example**:
    ```bash
    curl -X POST -H "X-CI-API-KEY: your_api_key" https://yourwebsite.com/wp-json/ci-site-manager/v1/maintenance/disable
    ```

- `GET /maintenance/status`: Check if maintenance mode is active.
  - **Example**:
    ```bash
    curl -H "X-CI-API-KEY: your_api_key" https://yourwebsite.com/wp-json/ci-site-manager/v1/maintenance/status
    ```

### Site Option Management

- `GET /option/get/{option_name}`: Retrieve the value of a WordPress option.
  - **Example**:
    ```bash
    curl -H "X-CI-API-KEY: your_api_key" https://yourwebsite.com/wp-json/ci-site-manager/v1/option/get/blogname
    ```

- `POST /option/update/{option_name}`: Update the value of a WordPress option.
  - **Example**:
    ```bash
    curl -X POST -H "X-CI-API-KEY: your_api_key" -d '{"value": "New Blog Name"}' https://yourwebsite.com/wp-json/ci-site-manager/v1/option/update/blogname
    ```

### Post Management

- `GET /post/list`: List all posts.
  - **Example**:
    ```bash
    curl -H "X-CI-API-KEY: your_api_key" https://yourwebsite.com/wp-json/ci-site-manager/v1/post/list
    ```

- `POST /post/create`: Create a new post with title and content.
  - **Example**:
    ```bash
    curl -X POST -H "X-CI-API-KEY: your_api_key" -d '{"title": "New Post", "content": "This is the content of the new post."}' https://yourwebsite.com/wp-json/ci-site-manager/v1/post/create
    ```

- `DELETE /post/delete/{id}`: Delete a post by post ID.
  - **Example**:
    ```bash
    curl -X DELETE -H "X-CI-API-KEY: your_api_key" https://yourwebsite.com/wp-json/ci-site-manager/v1/post/delete/1
    ```

### Database Backup and Restore

- `POST /database/backup`: Backup the WordPress database.
  - **Example**:
    ```bash
    curl -X POST -H "X-CI-API-KEY: your_api_key" https://yourwebsite.com/wp-json/ci-site-manager/v1/database/backup
    ```

- `POST /database/restore`: Restore the WordPress database.
  - **Example**:
    ```bash
    curl -X POST -H "X-CI-API-KEY: your_api_key" -d '{"backup_file": "path/to/backup.sql"}' https://yourwebsite.com/wp-json/ci-site-manager/v1/database/restore
    ```

### Search-Replace

- `POST /search-replace`: Perform a find-and-replace operation across the WordPress database.
  - **Example**:
    ```bash
    curl -X POST -H "X-CI-API-KEY: your_api_key" -d '{"search": "oldtext", "replace": "newtext"}' https://yourwebsite.com/wp-json/ci-site-manager/v1/search-replace
    ```

## WP-CLI Commands

CI Rest Remote provides WP-CLI commands that mirror the REST API endpoints, making it possible to manage plugins, themes, users, and more directly from the command line. Run `wp ci:<command>` for each of the following actions:

### Plugin Management

- `wp ci:list-plugins`: List all installed plugins.
- `wp ci:activate-plugin <slug>`: Activate a plugin by slug.
- `wp ci:deactivate-plugin <slug>`: Deactivate a plugin by slug.
- `wp ci:update-plugin <slug>`: Update a plugin by slug.
- `wp ci:install-plugin <manifest>`: Install a plugin from a manifest.
- `wp ci:delete-plugin <slug>`: Delete a plugin by slug.

### Theme Management

- `wp ci:list-themes`: List all installed themes.
- `wp ci:activate-theme <slug>`: Activate a theme by slug.
- `wp ci:deactivate-theme <slug>`: Deactivate a theme by slug.
- `wp ci:update-theme <slug>`: Update a theme by slug.
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

### Post Management

- `wp ci:list-posts`: List all posts.
- `wp ci:create-post <title> <content>`: Create a new post.
- `wp ci:delete-post <id>`: Delete a post by ID.

### Database Backup and Restore

- `wp ci:backup-database`: Backup the WordPress database.
- `wp ci:restore-database <backup_file>`: Restore the WordPress database.

### Search-Replace

- `wp ci:search-replace <search> <replace>`: Perform a find-and-replace operation in the database.

## Admin Settings Page

CI Rest Remote includes a settings page in the WordPress admin for managing the API key:

1. **API Key**: The current API key is displayed in a read-only text box.
2. **Regenerate**: Click to generate a new API key, which is saved asynchronously via AJAX.
3. **Copy**: Copy the API key to your clipboard for easy sharing and access.

## License

This plugin is open-source and available under the MIT License.

## Contributions

Contributions and improvements are welcome! Feel free to fork this repository and submit pull requests to enhance functionality or fix bugs.

---

Enjoy using **CI Rest Remote** to streamline and simplify your WordPress management tasks!