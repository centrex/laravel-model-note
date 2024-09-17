<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    /*
     * The class name of the note model that holds all notes.
     *
     * The model must be or extend `Centrex\LaravelModelNote\ModelNote`.
     */
    'note_model' => Centrex\LaravelModelNote\ModelNote::class,

    /*
     * The name of the column which holds the ID of the model related to the notes.
     *
     * You can change this value if you have set a different name in the migration for the notes table.
     */
    'model_primary_key_attribute' => 'model_id',

    /*
    |--------------------------------------------------------------------------
    | Database Driver Configurations
    |--------------------------------------------------------------------------
    |
    | Available database drivers
    |
    */

    'drivers' => [
        'database' => [
            // 'connection' => 'example',
            'connection' => null,
        ],
    ],

];
