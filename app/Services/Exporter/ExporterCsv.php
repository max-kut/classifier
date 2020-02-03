<?php

namespace App\Services\Exporter;

use App\Models\Phrase;
use App\Models\User;
use Illuminate\Http\Response;
use League\Csv\Writer;

class ExporterCsv implements ExporterInterface
{
    /**
     * @var \App\Models\User
     */
    private $user;

    /**
     * @param \App\Models\User $user
     * @return \App\Services\Exporter\ExporterCsv
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return \Illuminate\Http\Response
     * @throws \League\Csv\CannotInsertRecord
     */
    public function export(): Response
    {
        return response((string) $this->getCsv(), 200, [
            'Content-Type' => 'text/csv',
            'Content-Transfer-Encoding' => 'binary',
            'Content-Disposition' => 'attachment; filename="people.csv"',
        ]);
    }

    /**
     * @return \League\Csv\Writer
     * @throws \League\Csv\CannotInsertRecord
     */
    private function getCsv()
    {
        $csv = Writer::createFromFileObject(new \SplTempFileObject());
        $csv->insertOne(['id', 'text', 'topic']);

        $this->user->phrases->each(function(Phrase $phrase)use($csv){
            $csv->insertOne($phrase->setVisible(['id', 'text', 'topic'])->toArray());
        });

        return $csv;
    }
}
