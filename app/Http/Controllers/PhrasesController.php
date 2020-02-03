<?php

namespace App\Http\Controllers;

use App\Events\CalculatingRunning;
use App\Http\Requests\PhraseRequest;
use App\Http\Requests\PhrasesImportRequest;
use App\Models\Phrase;
use App\Services\Exporter\ExporterInterface;
use App\Services\Importer\ImporterInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PhrasesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = ($perPage = $request->get('per_page', 20)) > 100 ? 100 : $perPage;

        return response()->json(Phrase::with(['proposed'])->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PhraseRequest $request)
    {
        $phrase = Phrase::create($request->validated());

        return response()->json(compact('phrase'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PhraseRequest $request, Phrase $phrase)
    {
        $phrase->update($request->validated());
        $phrase->load('proposed');

        return response()->json(compact('phrase'));
    }

    /**
     * @param \App\Http\Requests\PhraseRequest $request
     * @param \App\Models\Phrase $phrase
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function accept(PhraseRequest $request, Phrase $phrase)
    {
        $phrase->fill($request->validated());
        $phrase->accept()->save();

        return response()->json(compact('phrase'));
    }

    /**
     * @param \App\Http\Requests\PhraseRequest $request
     * @param \App\Models\Phrase $phrase
     * @return \Illuminate\Http\JsonResponse
     */
    public function reject(PhraseRequest $request, Phrase $phrase)
    {
        $phrase->fill($request->validated());
        $phrase->reject()->save();

        return response()->json(compact('phrase'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Phrase $phrase)
    {
        $phrase->delete();

        return response()->json(['ok' => true]);
    }

    /**
     * @param \App\Http\Requests\PhrasesImportRequest $request
     * @param \App\Services\Importer\ImporterInterface $importer
     * @return \Illuminate\Http\JsonResponse
     */
    public function import(PhrasesImportRequest $request, ImporterInterface $importer)
    {
        $count = $importer->setFile($request->file('file'))->import();

        return response()->json(compact('count'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Services\Exporter\ExporterInterface $exporter
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request, ExporterInterface $exporter)
    {
        return $exporter->setUser($request->user())->export();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculate(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        if ($user->isPendingCalculating()) {
            return response()->json([
                'message' => __('Calculating is already running')
            ], Response::HTTP_LOCKED);
        }

        event(new CalculatingRunning($user));

        return response()->json([
            'message' => __('Calculating started')
        ]);
    }
}
