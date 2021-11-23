<?php

namespace Underpin\Batch_Tasks;

use Underpin\Abstracts\Underpin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function batch_task_handler() {
	return Underpin::make_class( [
		'root_namespace'      => 'Underpin\Batch_Tasks',
		'text_domain'         => 'underpin-batch-tasks',
		'minimum_php_version' => '7.0',
		'minimum_wp_version'  => '5.1',
		'version'             => '1.0.0',
	] )->get( __FILE__ );
}

//set up handler
batch_task_handler();

// Enqueue scripts and styles to batch task.
Underpin::attach( 'setup', new \Underpin\Factories\Observer( 'batch_tasks', [
	'update' => function ( Underpin $plugin ) {

		// Register core-specific items.
		if ( $plugin->file() === __FILE__ ) {
			$dir_url = plugin_dir_url( __FILE__ );

			// Register the batch JS
			$plugin->scripts()->add( 'batch', [
				'class' => 'Underpin\Scripts\Factories\Script_Instance',
				'args'  => [
					[
						'handle'      => 'underpin_batch',
						'deps'        => [ 'jquery' ],
						'description' => 'Script that handles batch tasks.',
						'name'        => "Batch Task Runner Script",
						'in_footer'   => true,
						'src'         => $plugin->url() . 'assets/js/build/batch.min.js',
						'version'     => '1.0.0',
					],
				],
			] );

			// Localize the ajax URL on the batch JS, if it was registered successfully.
			if ( ! is_wp_error( $plugin->scripts()->get( 'batch' ) ) ) {
				$plugin->scripts()->get( 'batch' )->set_param( 'ajaxUrl', admin_url( 'admin-ajax.php' ) );
			}

			// Register the batch stylesheet
			$plugin->styles()->add( 'batch', [
				'class' => 'Underpin\Styles\Factories\Style_Instance',
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
	}
] ) );

// Add this loader.
Underpin::attach( 'setup', new \Underpin\Factories\Observers\Loader( 'batch_tasks', [
	'class' => 'Underpin\Batch_Tasks\Loaders\Batch_Tasks',
] ) );
