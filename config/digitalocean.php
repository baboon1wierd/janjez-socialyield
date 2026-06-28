<?php

return [
    'digitalocean' => [
        'agent_url' => env('DO_AGENT_URL', 'https://your-digitalocean-agent.com'),
        'api_key' => env('DO_AGENT_API_KEY', 'your_api_key_here'),
    ],
];