<?php

namespace App\Services\Importer;

use App\Models\Phrase;
use App\Models\User;
use League\Csv\Reader;

class CsvImporter implements ImporterInterface
{
    /**
     * @var \App\Models\User
     */
    private $user;
    /**
     * @var \League\Csv\Reader
     */
    private $file;
    /**
     * @var \League\Csv\Reader
     */
    private $reader;

    /**
     * @inheritDoc
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param \Illuminate\Http\UploadedFile $file
     * @return \App\Services\Importer\CsvImporter
     */
    public function setFile($file)
    {
        $this->reader = Reader::createFromFileObject($file->openFile());

        return $this;
    }

    /**
     * @inheritDoc
     * @throws \League\Csv\Exception
     */
    public function import()
    {
        // очистим фразы пользователя перед загрузкой новых
        $this->user->phrases->each->delete();

        $phrases = $this->getPhrasesCollection();

        $phrasesIds = $phrases->pluck('id')->unique()->filter();
        // проверяем идентификаторы фраз.
        $existsPhrases = Phrase::whereIn('id', $phrasesIds)->get(['id'])->keyBy('id');
        if($existsPhrases->isNotEmpty()){
            $phrases = $phrases->filter(function(Phrase $phrase)use($existsPhrases){
                // Если импортированная Фраза существует в базе у дрого пользователя - мы ее не сохраняем
                return !$existsPhrases->has($phrase->id);
            });
        }

        $this->user->phrases()->saveMany($phrases);

        return $phrases->count();
    }

    /**
     * @return \Illuminate\Support\Collection
     * @throws \League\Csv\Exception
     */
    private function getPhrasesCollection()
    {
        $phrases = collect();
        $this->reader->setHeaderOffset(0);
        $header = $this->reader->getHeader();

        foreach ($this->reader->getRecords($header) as $offset => $record) {
            $phrases->push(Phrase::make($record));
        }

        return $phrases;
    }
}
