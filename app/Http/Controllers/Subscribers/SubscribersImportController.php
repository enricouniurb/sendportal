<?php

declare(strict_types=1);

namespace App\Http\Controllers\Subscribers;

use App\Http\Middleware\OwnsCurrentWorkspace;
use Sendportal\Base\Services\Subscribers\ImportSubscriberService;
use Sendportal\Base\Http\Controllers\Subscribers\SubscribersImportController as BaseSubscribersImportController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;
use Sendportal\Base\Http\Requests\SubscribersImportRequest;
use Sendportal\Base\Facades\Sendportal;

class SubscribersImportController extends BaseSubscribersImportController
{    

    public function __construct(ImportSubscriberService $subscriberService)
    {
        parent::__construct($subscriberService);

        $this->middleware(OwnsCurrentWorkspace::class)->only([
            'show',
            'store',           
        ]);
    }

    /**
     * @throws IOException
     * @throws UnsupportedTypeException
     * @throws ReaderNotOpenedException
     */
    public function store(SubscribersImportRequest $request): RedirectResponse
    {
        if ($request->file('file')->isValid()) {
            $filename = Str::random(16) . '.csv';

            $path = $request->file('file')->storeAs('imports', $filename, 'local');

            $errors = $this->validateCsvContents(Storage::disk('local')->path($path));

            if (count($errors->getBags())) {
                Storage::disk('local')->delete($path);

                return redirect()->back()
                    ->withInput()
                    ->with('error', __('The provided file contains errors'))
                    ->with('errors', $errors);
            }

            $counter = [
                'created' => 0,
                'updated' => 0
            ];

            (new FastExcel)->import(Storage::disk('local')->path($path), function (array $line) use ($request, &$counter) {
                $data = Arr::only($line, ['id', 'email', 'first_name', 'last_name','text1','text2']);

                $data['tags'] = $request->get('tags') ?? [];
                $subscriber = $this->subscriberService->import(Sendportal::currentWorkspaceId(), $data);

                if ($subscriber->wasRecentlyCreated) {
                    $counter['created']++;
                } else {
                    $counter['updated']++;
                }
            });

            Storage::disk('local')->delete($path);

            return redirect()->route('sendportal.subscribers.index')
                ->with('success', __('Imported :created subscriber(s) and updated :updated subscriber(s) out of :total', [
                    'created' => $counter['created'],
                    'updated' => $counter['updated'],
                    'total' => $counter['created'] + $counter['updated']
                ]));
        }

        return redirect()->route('sendportal.subscribers.index')
            ->with('errors', __('The uploaded file is not valid'));
    }

    /**
     * @param string $path
     * @return ViewErrorBag
     * @throws IOException
     * @throws ReaderNotOpenedException
     * @throws UnsupportedTypeException
     */
    protected function validateCsvContents(string $path): ViewErrorBag
    {
        $errors = new ViewErrorBag();

        $row = 1;

        (new FastExcel)->import($path, function (array $line) use ($errors, &$row) {
            $data = Arr::only($line, ['id', 'email', 'first_name', 'last_name','text1','text2']);

            try {
                $this->validateData($data);
            } catch (ValidationException $e) {
                $errors->put('Row ' . $row, $e->validator->errors());
            }

            $row++;
        });

        return $errors;
    }



}