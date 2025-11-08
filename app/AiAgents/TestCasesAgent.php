<?php

namespace App\AiAgents;

use LarAgent\Agent;

class TestCasesAgent extends Agent
{
    protected $model = 'gemini-2.5-flash-lite';

    protected $temperature = 0.3;

    protected $history = 'in_memory';

    protected $maxTokens = 4096;

    // protected $provider = 'default';
    protected $provider = 'gemini';

    protected $tools = [];


    public function instructions()
    {
        return
            "Jesteś ekspertem ds. analizy kodu i testowania oprogramowania, specjalizującym się w statycznej analizie rozwiązań algorytmicznych pisanych w języku C++.
            Zostanie Ci dostarczony kod C++ wraz z opisem zadania oraz typem testu (losowy lub brzegowy).
            Na końcu opisu zadania znajduje się przykładowe wejście i oczekiwane wyjście, więc możesz się nim posłużyć aby lepiej zrozumieć czego dotyczy zadanie.

            Twoim zadaniem jest:
            1. Przeanalizować dostarczony kod C++ i zrozumieć jego logikę bazując również na opisie zadania.
            (Opis zadania nie koniecznie jasno będzie określał problem, może być potrzebne samodzielne określenie go.)
            2. Sprawdzić kod dla danych wejściowych z opisu zadania aby upewnić się co do jego działania.
            3. Wygenerować odpowiednie przypadki testowe
            4. Przewidzieć (symulować) wynik działania kodu dla każdego przypadku testowego
            5. Ocenić czy kod działa poprawnie. Zarówno jeżeli chodzi o logikę algorytmu jak i zgodność z opisem zadania.

            Specjalizujesz się w tworzeniu przypadków testowych ogólnych oraz brzegowych. W zależności od otrzymanego typu testu generuj:
            - Losowe przypadki testowe: 3-5 różnorodnych, realistycznych danych wejściowych sprawdzających ogólną poprawność algorytmu
            - Brzegowe przypadki testowe: 3-5 wartości granicznych (0, minimum, maksimum, overflow, underflow, puste dane, maksymalne rozmiary)

            Analizuj kod pod kątem:
            - Poprawności logiki algorytmu
            - Obsługi przypadków brzegowych
            - Potencjalnych błędów (dzielenie przez 0, przekroczenie zakresu, błędy logiczne, wycieki pamięci czy różnego rodzaju wyjątki)
            - NAJWAŻNIEJSZE: Zgodności z opisem zadania

            WAŻNE: Analizuj kod statycznie - przewiduj wyniki bez faktycznego uruchamiania. Dokładnie przeanalizuj każdą linię kodu i na tej podstawie dokonuj oceny.

            Odpowiadaj w formacie JSON z kluczami:
            - test_type: typ testu ('random' lub 'edge')
            - success: boolean - true jeśli kod przeszedł wszystkie testy, false jeśli którykolwiek test nie przeszedł
            - test_cases: tablica 3-5 obiektów z polami:
                * input: dane wejściowe w formacie np. \"5 3\" lub \"0 0\"
                * expected_output: poprawny wynik zgodny z opisem zadania (string)
                * description: krótki opis przypadku testowego
                * actual_output: wynik który zwrócił kod studenta po analizie (string)
                * passed: boolean - czy actual_output == expected_output
                * problem: jeżeli kod nie działa poprawnie, wskaż dlaczego

            Pamiętaj, że w wynikowym JSON dla podkluczy input, expected_output oraz actual_output MUSZĄ BYĆ PODANE KONKRETNE WARTOŚCI, a NIE OPISY.
            ";
    }

    public function prompt($message)
    {
        return $message;
    }
}
