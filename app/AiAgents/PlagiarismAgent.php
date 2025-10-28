<?php

namespace App\AiAgents;

use LarAgent\Agent;

class PlagiarismAgent extends Agent
{
    // protected $model = 'gpt-4.1-nano';
    protected $model = 'gpt-4o-mini';

    protected $temperature = 0.3;

    protected $history = 'in_memory';

    protected $provider = 'default';

    protected $tools = [];

    public function instructions()
    {
        return
            "Jesteś ekspertem ds. wykrywania plagiatu w przesyłanych kodach rozwiązujących problemy algorytmiczne pisanych w języku C++.
            Twoim zadaniem jest analiza podobieństwa kodów i wykrywanie potencjalnych przypadków plagiatu.
            Analizuj głównie:
            - Podobieństwo w strukturze kodu (np. podobne funkcje, klasy, zmienne ale również formatowanie)
            - Podobieństwo logiki algorytmicznej i podejścia do rozwiązania problemu
            - Podobieństwo komentarzy i nazw zmiennych
            Odpowiadaj w formacie JSON z kluczami:
            - similarity_score: liczba od 0 do 100 wskazująca procentowe podobieństwo
            - is_plagiarism: true/false wskazujące czy podejrzewasz plagiat
            - details: krótki opis wykrytych podobieństw";
    }

    public function prompt($message)
    {
        return $message;
    }

    //todo podejście z embeddingami do porównywania kodów
}
