# Underpin batch task Loader

Loader That assists with adding batch tasks to a WordPress website.

## Installation

### Using Composer

`composer require underpin/decision-list-loader`

### Manually

This plugin uses a built-in autoloader, so as long as it is required _before_
Underpin, it should work as-expected.

`require_once(__DIR__ . '/underpin-decision-lists/decision-lists.php');`

## Setup

1. Install Underpin. See [Underpin Docs](https://www.github.com/underpin-wp/underpin)
1. Register new batch tasks as-needed.

## Batch tasks

As a plugin matures, the need to break a big task (like replacing the value of every record in a database) into
smaller tasks (replace 20 records until all are replaced) becomes commonplace.

The problem is, WordPress doesn't provide a way to create these tasks easily. This loader makes it possible to register
and build your own batch tasks quickly.

## Example

A very basic example could look something like this.

```php
\Underpin\underpin()->batch_tasks()->add( 'example-batch', [
	'class' => 'Underpin_Batch_Tasks\Factories\Batch_Task_Instance',
	'args'  => [
		[
			'description'             => 'A batch task that does nothing 20 times',
			'name'                    => 'Batch Task Example',
			'tasks_per_request'       => 50,
			'stop_on_error'           => true,
			'total_items'             => 1000,
			'notice_message'          => 'Run the most pointless batch task ever made.',
			'button_text'             => 'LETS GO.',
			'capability'              => 'administrator',
			'batch_id'                => 'example-batch',
			'task_callback'           => '__return_null', // The callback that iterates on every task
			'finish_process_callback' => '__return_null', // The callback that runs after everything is finished
			'prepare_task_callback'   => '__return_null', // The callback that runs before each task
			'finish_task_callback'    => '__return_null', // The callback that runs after each task
		],
	],
] );
```

Alternatively, you can extend `batch task` and reference the extended class directly, like so:

```php
underpin()->batch_tasks()->add('key','Namespace\To\Class');
```

This is especially useful when using batch tasks, since they have a tendency to get quite long, and nest deep.

## Enqueuing the batch notice

Once a batch task is registered, you can instruct Underpin to display the admin notice to run this task like so:

```php
Underpin\underpin()->batch_tasks()->enqueue('example-batch');
```

This will add a batch task notice to the admin area. Once clicked, the batch task will run, with a progress bar
to indicate progress. When complete, it will self-close.

![image](https://user-images.githubusercontent.com/8210827/116898675-87dcf080-abeb-11eb-9514-0f31566e90ca.png)