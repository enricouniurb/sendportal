<?php

declare(strict_types=1);

namespace App\Http\Controllers\Workspaces;

use App\Http\Controllers\Controller;
use App\Http\Requests\FromEmails\FromEmailStoreRequest;
use App\Http\Requests\FromEmails\FromEmailUpdateRequest;
use App\Repositories\FromEmailRepository;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Sendportal\Base\Facades\Sendportal;
use App\Http\Middleware\OwnsCurrentWorkspace;

class FromEmailsController extends Controller
{
    /** @var FromEmailRepository */
    private $fromEmailsRepo;

    public function __construct(FromEmailRepository $fromEmailsRepo)
    {
        $this->fromEmailsRepo = $fromEmailsRepo;

        $this->middleware(OwnsCurrentWorkspace::class)->only([            
            'store',
            'destroy',                      
        ]);
    }

    /**
     * @throws Exception
     */
    public function index(): View
    {
        $emails = $this->fromEmailsRepo->all(Sendportal::currentWorkspaceId());

        return view('from-emails.index', compact('emails'));
    }

    public function create(): View
    {
        return view('from-emails.create');
    }

    /**
     * @throws Exception
     */
    public function store(FromEmailStoreRequest $request): RedirectResponse
    {
        $this->fromEmailsRepo->store(Sendportal::currentWorkspaceId(), $request->all());

        return redirect()->route('from-emails.index');
    }

    /**
     * @throws Exception
     */
    public function edit(int $id): View
    {
        $email = $this->fromEmailsRepo->find(Sendportal::currentWorkspaceId(), $id);

        return view('from-emails.edit', compact('email'));
    }

    /**
     * @throws Exception
     */
    public function update(int $id, FromEmailUpdateRequest $request): RedirectResponse
    {
        $this->fromEmailsRepo->update(Sendportal::currentWorkspaceId(), $id, $request->all());

        return redirect()->route('from-emails.index');
    }

    /**
     * @throws Exception
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->fromEmailsRepo->destroy(Sendportal::currentWorkspaceId(), $id);

        return redirect()->route('from-emails.index');
    }
}
