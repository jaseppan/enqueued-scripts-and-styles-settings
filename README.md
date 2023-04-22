# Enqueued Scripts and Styles Editor
Enqueued Scripts and Styles is a WordPress plugin that allows you to modify how enqueued scripts and styles are loaded on your site. With this plugin, you can select whether to load scripts and styles asynchronously, defer their loading, disable them completely, or leave them as they are.

## Installation
To install the plugin, follow these steps:

Download the plugin ZIP file from the WordPress plugin repository.
Upload the ZIP file to your WordPress site by navigating to Plugins > Add New and clicking the "Upload Plugin" button.
Activate the plugin through the "Plugins" menu in WordPress.
Usage
To use the plugin, go to the "Enqueued Scripts and Styles" settings page in the WordPress admin dashboard. On this page, you'll see a list of all enqueued scripts and styles on your site, along with a dropdown menu for each one. Use the dropdown menu to select how the script or style should be loaded: none, async, defer, or disable.

Selected options are saved to the database and are used to modify the enqueued scripts and styles on page load. If the selected option is async or defer, the script will be loaded with the async or defer attribute. If the selected option is disable, the script will be deregistered and will not be loaded at all.

# Contributing
If you find a bug or have a feature request, please open an issue on the GitHub repository. Pull requests are also welcome!

# License
Enqueued Scripts and Styles is released under the MIT License.