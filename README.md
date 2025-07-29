# WorkCity Client Projects - WordPress Plugin

This plugin provides a simple and powerful way to manage project information directly within your WordPress dashboard. It creates a dedicated "Client Project" post type, adds custom fields for project details, and provides a shortcode to display the projects on your website's frontend.

This is an ideal solution for teams who use WordPress as their main website and need an easy way for non-developers to manage a portfolio of projects.

## Features

- **Custom Post Type**: Creates a "Client Projects" section in the WordPress admin menu, completely separate from regular posts and pages.
- **Custom Meta Fields**: Adds specific fields to each project:
  - Client Name (Text Input)
  - Status (Dropdown: Not Started, In Progress, Completed)
  - Deadline (Date Picker)
- **Easy Content Management**: Uses the standard WordPress title for the "Project Title" and the main editor for the "Project Description".
- **Display Shortcode**: A simple shortcode `[client_projects]` renders a clean, styled table of all your projects on any page or post.
- **REST API Enabled**: The custom post type is automatically exposed to the WordPress REST API for potential headless integration.

---

## Installation

You can install this plugin on any self-hosted WordPress website (WordPress.org).

1.  **Create the Plugin File**:

    - Create a new file on your computer named `workcity-projects-plugin.php`.
    - Copy the entire PHP code for the plugin into this file.

2.  **Create a ZIP Archive**:

    - Place the `workcity-projects-plugin.php` file into a new folder (e.g., `workcity-projects`).
    - Compress this folder into a `.zip` file.

3.  **Upload to WordPress**:

    - Log in to your WordPress admin dashboard.
    - Navigate to **Plugins > Add New**.
    - Click the **Upload Plugin** button at the top of the page.
    - Choose the `.zip` file you just created and click **Install Now**.

4.  **Activate**:
    - Once the installation is complete, click the **Activate Plugin** button.

---

## How to Use

### Adding a New Project

1.  After activating the plugin, a new **Client Projects** menu item will appear in your WordPress admin sidebar.
2.  Click on **Client Projects > Add New**.
3.  Fill in the project details:
    - **Title**: Use the main title field at the top for the project's name.
    - **Description**: Use the main content editor for the project's description.
    - **Project Details Box**: Below the editor, you will find a box where you can enter the **Client Name**, select the **Status**, and choose a **Deadline**.
4.  Click the **Publish** button.

### Displaying Projects on Your Website

1.  Navigate to any page or post where you want the project list to appear.
2.  Edit the page using the WordPress editor.
3.  Add a "Shortcode" block or a standard paragraph block.
4.  Type the following shortcode into the block:
    ```
    [client_projects]
    ```
5.  Save or update the page.

When you view the page, the shortcode will be replaced by a styled HTML table listing all of your published client projects.
