<?php

use function Underpin\underpin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Add this loader.
add_action( 'underpin/before_setup', function ( $instance ) {
	$class = get_class( $instance );

	if ( ! defined( 'UNDERPIN_BATCH_TASKS_ROOT_DIR' ) ) {
		define( 'UNDERPIN_BATCH_TASKS_ROOT_DIR', plugin_dir_path( __FILE__ ) );
	}

	require_once( UNDERPIN_BATCH_TASKS_ROOT_DIR . 'lib/loaders/Batch_Tasks.php' );
	require_once( UNDERPIN_BATCH_TASKS_ROOT_DIR . 'lib/abstracts/Batch_Task.php' );
	require_once( UNDERPIN_BATCH_TASKS_ROOT_DIR . 'lib/factories/Batch_Task_Instance.php' );

	// Register the loader
	$instance->loaders()->add( 'batch_tasks', [
		'registry' => 'Underpin_Batch_Tasks\Loaders\Batch_Tasks',
	] );

	// Register core-specific items.
	if ( 'Underpin\Underpin' === $class ) {
		$dir_url = plugin_dir_url( __FILE__ );

		// Register the batch JS
		underpin()->scripts()->add( 'batch', [
			'class' => 'Underpin_Scripts\Factories\Script_Instance',
			'args'  => [
				[
					'handle'      => 'underpin_batch',
					'deps'        => [ 'jquery' ],
					'description' => 'Script that handles batch tasks.',
					'name'        => "Batch Task Runner Script",
					'in_footer'   => true,
					'src'         => $dir_url . 'assets/js/build/batch.min.js',
					'version'     => '1.0.0',
				],
			],
		] );

		// Localize the ajax URL on the batch JS, if it was registered successfully.
		if ( ! is_wp_error( underpin()->scripts()->get( 'batch' ) ) ) {
			underpin()->scripts()->get( 'batch' )->set_param( 'ajaxUrl', admin_url( 'admin-ajax.php' ) );
		}

		// Register the batch stylesheet
		underpin()->styles()->add( 'batch', [
			'class' => 'Underpin_Styles\Factories\Style_Instance',
			'args'  => [
				[
					'handle'      => 'underpin_batch',
					'description' => 'Styles for batch tasks.',
					'name'        => "Batch Task Runner Styles",
					'src'         => $dir_url . 'assets/css/build/batchStyle.min.css',
				],
			],
		] );
	}
} );