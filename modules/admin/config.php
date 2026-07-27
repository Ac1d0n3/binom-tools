<?php

/**
 * Thin Admin Hub config — paths only; content stays under content/.
 */
return [
    'stories_path' => env('BINOM_TOOLS_CONTENT_PATH', base_path('content')).'/stories',
    'sprint_plans_path' => env('BINOM_TOOLS_SPRINT_PLANS_PATH', base_path('content/sprint-plans')),
    'playbook_images_path' => public_path('images/playbooks'),
];
