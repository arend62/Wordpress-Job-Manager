<?php
/**
 * Plugin Name:Wpjm_akoor
 * Description: Adds an extra jobdescription field to WP Job Manager job listings
 * Version: 1.0.1
 * Author: Akoor
  */

/**
 * Prevent direct access data leaks
 **/
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'gma_wpjmef_add_support_link_to_plugin_page' );

// Submit form filters
add_filter( 'submit_job_form_fields', 'gma_wpjmef_frontend_add_jobdescription_field');

// Text fields filters
add_filter( 'job_manager_job_listing_data_fields', 'gma_wpjmef_admin_add_jobdescription_field' ); // #

// Single Job page filters
add_action( 'single_job_listing_meta_end', 'gma_wpjmef_display_job_jobdescription_data' );

// Dashboard: Job Listings > Jobs filters
add_filter( 'manage_edit-job_listing_columns', 'gma_wpjmef_retrieve_jobdescription_column' );
add_filter( 'manage_job_listing_posts_custom_column', 'gma_wpjmef_display_jobdescription_column' );

/**
* Sets the job_jobdescription metadata as a new $column that can be used in the back-end
**/
function gma_wpjmef_retrieve_jobdescription_column($columns){

  $columns['job_jobdescription']         = __( 'jobdescription', 'wpjm_akoor' );
  return $columns;

};

/**
* Adds a new case to WP-Job-Manager/includes/admin/class-wp-job-manager-cpt.php
**/

function gma_wpjmef_display_jobdescription_column($column){
  
  global $post;

  switch ($column) {    
    case 'job_jobdescription':
      
      $jobdescription = get_post_meta( $post->ID, '_job_jobdescription', true );
      
      if ( !empty($jobdescription)) {
        echo $jobdescription;
      } else {
        echo '-';
      
      }
    break;
  }

  return $column;

};


/**
* Adds a new optional "jobdescription" text field at the "Submit a Job" form, generated via the [submit_job_form] shortcode
**/
function gma_wpjmef_frontend_add_jobdescription_field( $fields ) {
  
  $fields['job']['job_jobdescription'] = array(
    'label'       => __( 'Jobdescription', 'wpjm_akoor' ),
    'type'        => 'file',
    'required'    => false,
    'placeholder' => '',
    'description' => '',
    'priority'    => 9,
  );

  return $fields;

}

/**
* Adds a text field to the Job Listing wp-admin meta box named “jobdescription”
**/
function gma_wpjmef_admin_add_jobdescription_field( $fields ) {
  
  $fields['_job_jobdescription'] = array(
    'label'       => __( 'Jobdescription', 'wpjm_akoor' ),
    'type'        => 'text',
    'placeholder' => '',
    'description' => ''
  );

  return $fields;

}


/**
* Displays "jobdescription" on the Single Job Page, by checking if meta for "_job_jobdescription" exists and is displayed via do_action( 'single_job_listing_meta_end' ) on the template
**/
function gma_wpjmef_display_job_jobdescription_data() {
  
  global $post;

  $jobdescription = get_post_meta( $post->ID, '_job_jobdescription', true );


  if ( $jobdescription ) {
    echo '<a class="wpjmef-field-jobdescription">' href=" '.( $jobdescription ).'</a>';
  }

}

/**
 * Display an error message notice in the admin if WP Job Manager is not active
 */
function gma_wpjmef_admin_notice__error() {
	
  $class = 'notice notice-error';
	$message = __( 'An error has occurred. WP Job Manager must be installed in order to use this plugin', 'wpjm_akoor' );

	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 

}