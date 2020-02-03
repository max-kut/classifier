<?php

namespace App\Jobs;

use App\Events\CalculatingCompleted;
use App\Models\User;
use App\Services\Classifier\ClassifierServiceInterface;
use App\Services\Classifier\PhraseClassifier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalculatingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \App\Models\User
     */
    private $user;

    public $tries = 2;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 300;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new PhraseClassifier($this->user, app(ClassifierServiceInterface::class)))->classify();
        $this->user->unsetRelation('phrases');
        event(new CalculatingCompleted($this->user));
    }
}
