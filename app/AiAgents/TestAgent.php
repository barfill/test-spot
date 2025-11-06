<?php

namespace App\AiAgents;

use LarAgent\Agent;

class TestAgent extends Agent
{
    // protected $model = 'gpt-4.1-nano';
    // protected $model = 'gemini-pro';


    protected $history = 'in_memory';

    // protected $provider = 'default';
    protected $provider = 'gemini';


    protected $tools = [];

    public function instructions()
    {
        return "Jesteś testowym agentem. Zwróć mi jakiś żart.";
    }

    public function prompt($message)
    {
        return json_encode([
            'success' => true,
            'message' => 'To jest testowa odpowiedź agenta.',
            'input' => $message,
        ]);
    }
}
