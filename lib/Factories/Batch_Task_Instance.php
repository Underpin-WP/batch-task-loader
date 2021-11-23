<?php
/**
 * Script Factory
 *
 * @since   1.0.0
 * @package Underpin\Abstracts
 */


namespace Underpin\Batch_Tasks\Factories;


use Underpin\Batch_Tasks\Abstracts\Batch_Task;
use Underpin\Traits\Instance_Setter;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Script_Instance
 * Handles creating custom admin bar menus
 *
 * @since   1.0.0
 * @package Underpin\Abstracts
 */
class Batch_Task_Instance extends Batch_Task {
	use Instance_Setter;

	protected $task_callback           = '';
	protected $finish_process_callback = '__return_null';
	protected $prepare_task_callback   = '__return_null';
	protected $finish_task_callback    = '__return_null';

	public function __construct( $args = [] ) {
		// Override default params.
		$this->set_values( $args );
	}

	protected function task( $current_tally, $iteration ) {
		return $this->set_callable( $this->task_callback, $current_tally, $iteration );
	}

	protected function finish_process( $current_tally ) {
		return $this->set_callable( $this->finish_process_callback, $current_tally );
	}

	protected function prepare_task( $current_tally ) {
		return $this->set_callable( $this->prepare_task_callback, $current_tally );
	}

	protected function finish_task( $current_tally ) {
		return $this->set_callable( $this->finish_task_callback, $current_tally );
	}

}