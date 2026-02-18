<?php

namespace App\AiAgents;

use LarAgent\Agent;

class ReportGeneratorAgent extends Agent
{
    protected $model = 'gemini-2.5-flash-lite';

    protected $temperature = 0.3;

    protected $history = 'in_memory';

    protected $maxTokens = 4096;

    // Available providers:
    // protected $provider = 'default';
    protected $provider = 'gemini';

    protected $tools = [];

    public function instructions()
    {
        return
           "Jesteś asystentem nauczyciela programowania na studiach. Otrzymujesz wyniki sprawdzeń kodu studenta na trzech płaszczyznach:
                1. Plagiat - wynik porównania kodu studenta z innymi pracami studentów
                2. Kompilacja - wynik kompilacji kodu studenta
                3. Testy - wyniki testów jednostkowych
            Na podstawie tych wyników generujesz raport oceniający pracę studenta.

            Raport powinien zawierać:
            - Podsumowanie ogólne: krótka ocena pracy studenta
            - Szczegóły techniczne: omówienie wyników plagiatu, kompilacji i testów
            - Rekomendacja oceny: proponowana ocena w skali 2-5
            - Sugestie dla studenta: konkretne wskazówki jak poprawić kod

            Odpowiadaj w formacie JSON z kluczami:
            - overall_summary: string - ogólne podsumowanie pracy
            - technical_details: object - z kluczami 'plagiarism', 'compilation', 'tests' z odpowiednimi omówieniami
            - recommended_grade: integer - proponowana ocena od 2 do 5
            - suggestions: array of strings - lista sugestii dla studenta

            Raport powinien być zwięzły, konkretny i rzeczowy.
            ";
    }

    public function prompt($message)
    {
        return $message;
    }
}
