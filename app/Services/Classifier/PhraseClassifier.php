<?php

namespace App\Services\Classifier;

use App\Models\Phrase;
use App\Models\ProposedPhrase;
use App\Services\Classifier\Result\Result;
use App\Services\Classifier\Result\ResultCollection;
use App\Models\User;

class PhraseClassifier
{
    /**
     * @var \App\Models\User
     */
    private $user;
    /**
     * @var \App\Services\Classifier\ClassifierServiceInterface
     */
    private $service;

    /**
     * PhraseClassifier constructor.
     *
     * @param \App\Models\User $user
     * @param \App\Services\Classifier\ClassifierServiceInterface $service
     */
    public function __construct(User $user, ClassifierServiceInterface $service)
    {
        $this->user = $user->load('phrases');
        $this->service = $service;
    }

    public function classify(): void
    {
        if ($this->user->phrases->isEmpty()) {
            return;
        }
        $this->clearAllProposedPhrases();
        $this->saveNewProposedPhrases($this->service->calculate($this->user->phrases));
    }

    /**
     * Before Calculating we cleaning all proposed phrases
     */
    private function clearAllProposedPhrases()
    {
        $this->user->phrases->map(function (Phrase $phrase) {
            $phrase->proposed()->delete();
            $phrase->unsetRelation('proposed');
        });
    }

    /**
     * @param \App\Services\Classifier\Result\ResultCollection $results
     */
    private function saveNewProposedPhrases(ResultCollection $results): void
    {
        $phrases = $this->user->phrases->keyBy('id');

        $results->each(function (Result $result) use ($phrases) {
            /** @var \App\Models\Phrase|null $phrase */
            if ($result->isPredicted() && $phrase = $phrases->get($result->id)) {
                $phrase->proposed()->save(ProposedPhrase::make(['topic' => $result->topic]));
            }
        });
    }
}
