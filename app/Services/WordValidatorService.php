<?php
namespace App\Services;

class WordValidatorService
{
    /**
     * @var array
     */
    protected array $wordList;

    /**
     * Initialise the class
     */
    public function __construct()
    {
        $this->loadWordList();
    }

    /**
     * Load all valid English words
     *
     * @return void
     */
    protected function loadWordList(): void
    {
        $filename = app()->environment('testing')
            ? 'test_words.txt'
            : 'words.txt';

        $path = storage_path("app/dictionary/{$filename}");

        $this->wordList = file_exists($path)
            ? array_flip(array_map('trim', file($path)))
            : [];
    }

    /**
     * Check whether the input is a valid English word
     *
     * @param string $word
     * @return boolean
     */
    public function isValidEnglishWord(string $word): bool
    {
        return isset($this->wordList[strtolower($word)]);
    }

    /**
     * Check whether the input word can be constructed from the string
     *
     * @param string $word
     * @param string $available
     * @return boolean
     */
    public function canConstructFromAvailable(string $word, string $available): bool
    {
        $availableCounts = count_chars($available, 1);
        $wordCounts = count_chars($word, 1);

        foreach ($wordCounts as $ascii => $count) {
            if (!isset($availableCounts[$ascii]) || $availableCounts[$ascii] < $count) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get all valid words that can be formed from available letters
     * (Excludes already used words)
     *
     * @param string $available
     * @param array $exclude
     * @param boolean $exclude
     * @return array|boolean
     */
    public function findValidWords(string $available, array $exclude = [], bool $validWordPossibleCheck = false): array|bool
    {
        $validWords = [];

        foreach (array_keys($this->wordList) as $word) {
            if (in_array($word, $exclude)) {
                continue;
            }

            if ($this->canConstructFromAvailable($word, $available)) {
                $validWords[] = $word;
            }

            if ($validWordPossibleCheck && !empty($validWords)) {
                return true;
            }
        }

        return $validWords;
    }
}
