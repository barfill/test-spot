<?php

namespace App\AiAgents;

use LarAgent\Agent;

class TestAgent extends Agent
{
    // Available models:
    // protected $model = 'gpt-4.1-nano';
    // protected $model = 'gemini-pro';

    protected $history = 'in_memory';

    // Available providers:
    // protected $provider = 'default';
    protected $provider = 'gemini';


    protected $tools = [];

    public function instructions()
    {
        return "Testowy agent zwracający JSON z informacjami o przesłanym komunikacie.";
    }

    public function prompt($message)
    {
        return json_encode([
            'success' => true,
            'message' => 'Odpowiedź testowego agenta',
            'input' => $message,
        ]);
    }
}
