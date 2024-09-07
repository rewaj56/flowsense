
# FlowSense

FlowSense is a Laravel package that provides detailed route information. It includes a middleware that adds a floating button on your web pages to display the current route's details, such as URI, controller, and method.


## Features
- Route Information: Displays the current route's URI, controller, and method.
- Floating Button: Adds a button to your web pages for accessing route details.
- Customizable: Easily integrate and configure with any Laravel application.

## Screenshots

![App Screenshot](https://raw.githubusercontent.com/rewaj56/flowsense/main/screenshots/screenshot1.PNG)

![App Screenshot](https://raw.githubusercontent.com/rewaj56/flowsense/main/screenshots/screenshot2.PNG)


## Prerequisites
- **PHP >= 7.4 (7.4.33)**
- **Composer**
- **WAMP / XAMPP (for local development)**
## Installation

### Using Composer
To install FlowSense, add it to your Laravel project via Composer:

```bash
 composer require rewaj56/flowsense
```

### Register Service Provider
In Laravel, the package’s service provider should be automatically discovered. If not, add it manually to your config/app.php providers array:

```bash
 Rewaj56\Flowsense\Providers\FlowSenseServiceProvider::class,
```

### Add Middleware
The middleware is automatically registered to the web middleware group. Ensure your Kernel.php includes:

```bash
protected $middlewareGroups = [
    'web' => [
        // Other middleware...
        \Rewaj56\Flowsense\Http\Middleware\FlowSenseMiddleware::class,
    ],
];

```
    
## Usage

Once installed, a floating button (bottom left) will appear on your web pages, allowing you to view the current route's details.


## License

[MIT](https://choosealicense.com/licenses/mit/)

