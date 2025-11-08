<?php

namespace App\AiAgents;

use LarAgent\Agent;

class PlagiarismAgent extends Agent
{
    // protected $model = 'gpt-4.1-nano';
    // protected $model = 'gpt-4o-mini';
    // protected $model = 'gemini-2.0-flash-exp';
    // protected $model = 'gemini-2.5-flash';
    protected $model = 'gemini-2.5-flash-lite';


    protected $temperature = 0.3;

    protected $history = 'in_memory';

    // protected $provider = 'default';
    protected $provider = 'gemini';

    protected $tools = [];

    public function instructions()
    {
        return
            "Jesteś ekspertem ds. wykrywania plagiatu w przesyłanych kodach rozwiązujących problemy algorytmiczne pisanych w języku C++.
            Twoim zadaniem jest analiza podobieństwa kodów i wykrywanie potencjalnych przypadków plagiatu.

            Otrzymasz dwa fragmenty kodu C++ napisane przez różnych studentów rozwiązujących to samo zadanie programistyczne.
            Naturalne będzie użycie podobnych algorytmów i struktur danych do rozwiązania problemu.
            Logika prowadząca do rozwiązania również może być podobna ale Twoim zadaniem jest zidentyfikowanie nienaturalnych podobieństw które mogą wskazywać na plagiat.

            Plagiat to:
            - Identyczne lub bardzo podobne nazwy zmiennych
            - Identyczne komentarze lub ich tłumaczenia
            - Identyczne nietypowe formatowanie lub nietypowe podejście do problemu
            - Identyczne błędy lub nietypowe konstrukcje

            Skup się na analizie tych aspektów kodu, które wskazują na KOPIOWANIE, nie na podobne rozwiązanie tego samego problemu.

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
