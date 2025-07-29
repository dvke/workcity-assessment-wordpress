<?php
/**
 * Plugin Name:       WorkCity Client Projects
 * Description:       Adds a 'Client Projects' custom post type with custom fields and a display shortcode.
 * Version:           1.0.0
 * Author:            Your Name
 * Author URI:        https://yourwebsite.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       workcity-projects
 */

// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. Create the Custom Post Type 'Client Project'
 */
function workcity_register_project_cpt() {
    $labels = array(
        'name'                  => _x( 'Client Projects', 'Post type general name', 'workcity-projects' ),
        'singular_name'         => _x( 'Client Project', 'Post type singular name', 'workcity-projects' ),
        'menu_name'             => _x( 'Client Projects', 'Admin Menu text', 'workcity-projects' ),
        'name_admin_bar'        => _x( 'Client Project', 'Add New on Toolbar', 'workcity-projects' ),
        'add_new'               => __( 'Add New', 'workcity-projects' ),
        'add_new_item'          => __( 'Add New Client Project', 'workcity-projects' ),
        'new_item'              => __( 'New Client Project', 'workcity-projects' ),
        'edit_item'             => __( 'Edit Client Project', 'workcity-projects' ),
        'view_item'             => __( 'View Client Project', 'workcity-projects' ),
        'all_items'             => __( 'All Client Projects', 'workcity-projects' ),
        'search_items'          => __( 'Search Client Projects', 'workcity-projects' ),
        'parent_item_colon'     => __( 'Parent Client Projects:', 'workcity-projects' ),
        'not_found'             => __( 'No client projects found.', 'workcity-projects' ),
        'not_found_in_trash'    => __( 'No client projects found in Trash.', 'workcity-projects' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'client-project' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20, // Below 'Pages'
        'menu_icon'          => 'dashicons-portfolio',
        'supports'           => array( 'title', 'editor', 'thumbnail' ), // 'editor' is used for the main description
        'show_in_rest'       => true, // Important for bonus tasks (REST API)
    );

    register_post_type( 'client_project', $args );
}
add_action( 'init', 'workcity_register_project_cpt' );


/**
 * 2. Add Custom Meta Boxes for Project Details
 */
function workcity_add_meta_boxes() {
    add_meta_box(
        'workcity_project_details',                 // Unique ID
        'Project Details',                          // Box title
        'workcity_project_details_html',            // Content callback function
        'client_project',                           // Post type
        'normal',                                   // Context ('normal', 'side', 'advanced')
        'high'                                      // Priority
    );
}
add_action( 'add_meta_boxes', 'workcity_add_meta_boxes' );

/**
 * HTML for the 'Project Details' meta box.
 */
function workcity_project_details_html( $post ) {
    // Use a nonce for verification
    wp_nonce_field( 'workcity_save_project_details', 'workcity_project_nonce' );

    // Get existing meta values
    $client_name = get_post_meta( $post->ID, '_workcity_client_name', true );
    $status      = get_post_meta( $post->ID, '_workcity_status', true );
    $deadline    = get_post_meta( $post->ID, '_workcity_deadline', true );
    ?>
    <style>
        .workcity-meta-field { margin-bottom: 15px; }
        .workcity-meta-field label { display: block; font-weight: bold; margin-bottom: 5px; }
        .workcity-meta-field input, .workcity-meta-field select { width: 100%; max-width: 500px; padding: 8px; }
    </style>

    <div class="workcity-meta-field">
        <label for="workcity_client_name">Client Name</label>
        <input type="text" id="workcity_client_name" name="workcity_client_name" value="<?php echo esc_attr( $client_name ); ?>" />
    </div>

    <div class="workcity-meta-field">
        <label for="workcity_status">Status</label>
        <select id="workcity_status" name="workcity_status">
            <option value="Not Started" <?php selected( $status, 'Not Started' ); ?>>Not Started</option>
            <option value="In Progress" <?php selected( $status, 'In Progress' ); ?>>In Progress</option>
            <option value="Completed" <?php selected( $status, 'Completed' ); ?>>Completed</option>
        </select>
    </div>

    <div class="workcity-meta-field">
        <label for="workcity_deadline">Deadline</label>
        <input type="date" id="workcity_deadline" name="workcity_deadline" value="<?php echo esc_attr( $deadline ); ?>" />
    </div>
    <?php
    // Note: 'Title' is the standard post title.
    // 'Description' is the standard WordPress editor.
}

/**
 * 3. Save the Custom Meta Box Data
 */
function workcity_save_project_details( $post_id ) {
    // Check nonce
    if ( ! isset( $_POST['workcity_project_nonce'] ) || ! wp_verify_nonce( $_POST['workcity_project_nonce'], 'workcity_save_project_details' ) ) {
        return;
    }

    // Check if this is an autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Save Client Name
    if ( isset( $_POST['workcity_client_name'] ) ) {
        update_post_meta( $post_id, '_workcity_client_name', sanitize_text_field( $_POST['workcity_client_name'] ) );
    }

    // Save Status
    if ( isset( $_POST['workcity_status'] ) ) {
        update_post_meta( $post_id, '_workcity_status', sanitize_text_field( $_POST['workcity_status'] ) );
    }

    // Save Deadline
    if ( isset( $_POST['workcity_deadline'] ) ) {
        update_post_meta( $post_id, '_workcity_deadline', sanitize_text_field( $_POST['workcity_deadline'] ) );
    }
}
add_action( 'save_post', 'workcity_save_project_details' );


/**
 * 4. Create the Shortcode to Display Projects
 * Usage: [client_projects]
 */
function workcity_projects_shortcode( $atts ) {
    // Set default attributes
    $atts = shortcode_atts( array(
        'count' => -1, // -1 means show all
    ), $atts, 'client_projects' );

    $args = array(
        'post_type'      => 'client_project',
        'posts_per_page' => intval( $atts['count'] ),
        'post_status'    => 'publish',
    );

    $projects_query = new WP_Query( $args );

    // Start output buffering
    ob_start();

    if ( $projects_query->have_posts() ) {
        ?>
        <style>
            .workcity-projects-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            .workcity-projects-table th, .workcity-projects-table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
            .workcity-projects-table th { background-color: #f2f2f2; font-weight: bold; }
            .workcity-projects-table tr:nth-child(even) { background-color: #f9f9f9; }
            .workcity-projects-table tr:hover { background-color: #f1f1f1; }
            .workcity-project-description { margin-top: 5px; font-size: 0.9em; color: #555; }
        </style>
        <div class="workcity-projects-container">
            <table class="workcity-projects-table">
                <thead>
                    <tr>
                        <th>Project Title</th>
                        <th>Client Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Deadline</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ( $projects_query->have_posts() ) : $projects_query->the_post(); ?>
                        <?php
                        $client_name = get_post_meta( get_the_ID(), '_workcity_client_name', true );
                        $status      = get_post_meta( get_the_ID(), '_workcity_status', true );
                        $deadline    = get_post_meta( get_the_ID(), '_workcity_deadline', true );
                        ?>
                        <tr>
                            <td><strong><?php the_title(); ?></strong></td>
                            <td><?php echo esc_html( $client_name ); ?></td>
                            <td><div class="workcity-project-description"><?php the_content(); ?></div></td>
                            <td><?php echo esc_html( $status ); ?></td>
                            <td><?php echo esc_html( $deadline ? date( 'F j, Y', strtotime($deadline) ) : 'N/A' ); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php
    } else {
        echo '<p>No client projects found.</p>';
    }

    // Reset post data
    wp_reset_postdata();

    // Return the buffered content
    return ob_get_clean();
}
add_shortcode( 'client_projects', 'workcity_projects_shortcode' );
