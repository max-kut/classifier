<?php

namespace App\Services\Classifier;

use App\Models\Phrase;
use App\Services\Classifier\Result\Result;
use App\Services\Classifier\Result\ResultCollection;
use Illuminate\Support\Collection;

class FakeClassifierService implements ClassifierServiceInterface
{
    /**
     * @var \Faker\Generator
     */
    private $fakerGenerator;

    /**
     * FakeClassifierService constructor.
     */
    public function __construct()
    {
        $this->fakerGenerator = \Faker\Factory::create();
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function calculate(Collection $phrases): ResultCollection
    {
        sleep(random_int(30, 90));

        return ResultCollection::make($phrases->toBase()->map(function (Phrase $phrase) {
            return Result::make([
                'id'     => $phrase->id,
                'text'   => $phrase->text,
                'topic'  => $phrase->topic ?: $this->fakerGenerator->text(random_int(50, 250)),
                'source' => $phrase->topic ? static::SOURCE_TRAINED : static::SOURCE_PREDICTED
            ]);
        }));
    }
}
