<?php

return [

    // All the sections for the settings page
    'sections' => [
        'app' => [
            'title' => 'General Settings',
            'descriptions' => 'Application general settings.', // (optional)
            'icon' => 'fa fa-cog', // (optional)

            'inputs' => [
                [
                    'name' => 'nama_aplikasi',
                    'type' => 'text',
                    'label' => 'Nama Aplikasi',
                    'class' => 'form-control',
                    'style' => '',
                    'rules' => 'required|min:2|max:50',
                    'value' => 'ASKLIN'
                ],
                [
                    'name' => 'logo',
                    'type' => 'image',
                    'label' => 'Upload logo',
                    'hint' => 'Must be an image and cropped in desired size',
                    'rules' => 'image|max:2000',
                    'disk' => 'public',
                    'path' => 'app',
                    'preview_class' => 'thumbnail',
                    'preview_style' => 'height:40px'
                ],
                [
                    'name' => 'favicon',
                    'type' => 'image',
                    'label' => 'Upload favicon',
                    'hint' => 'Must be an image and cropped in desired size',
                    'rules' => 'image|max:2000',
                    'disk' => 'public',
                    'path' => 'app',
                    'preview_class' => 'thumbnail',
                    'preview_style' => 'height:40px'
                ]
            ]
        ]
    ],

    // Setting page url, will be used for get and post request
    'url' => 'pengaturan',

    // Any middleware you want to run on above route
    'middleware' => ['role:Superadmin'],

    // Route Names
    'route_names' => [
        'index' => 'settings.index',
        'store' => 'settings.store',
    ],
    
    // View settings
    'setting_page_view' => 'backend.secret.settings',
    'flash_partial' => 'app_settings::_flash',

    // Setting section class setting
    'section_class' => 'card mb-3',
    'section_heading_class' => 'card-header',
    'section_body_class' => 'card-body',

    // Input wrapper and group class setting
    'input_wrapper_class' => 'form-group',
    'input_class' => 'form-control',
    'input_error_class' => 'has-error',
    'input_invalid_class' => 'is-invalid',
    'input_hint_class' => 'form-text text-muted',
    'input_error_feedback_class' => 'text-danger',

    // Submit button
    'submit_btn_text' => 'Simpan',
    'submit_success_message' => 'Settings has been saved.',

    // Remove any setting which declaration removed later from sections
    'remove_abandoned_settings' => false,

    // Controller to show and handle save setting
    'controller' => '\QCod\AppSettings\Controllers\AppSettingController',

    // settings group
    'setting_group' => function() {
        // return 'user_'.auth()->id();
        return 'default';
    }
];
