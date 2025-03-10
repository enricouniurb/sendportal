<?php

declare(strict_types=1);

namespace App\Http\Controllers\Workspaces;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\Workspaces\RemoveUserFromWorkspace;
use App\Services\Workspaces\AddUserToWorkspace;
use App\Http\Requests\Workspaces\WorkspaceAddUserRequest;
use Sendportal\Base\Facades\Sendportal;

class WorkspaceUsersController extends Controller
{
    /** @var RemoveUserFromWorkspace */
    private $removeUserFromWorkspace;
    
    /** @var AddUserToWorkspace */
    private $addUserToWorkspace;

    public function __construct(RemoveUserFromWorkspace $removeUserFromWorkspace, AddUserToWorkspace $addUserToWorkspace)
    {
        $this->removeUserFromWorkspace = $removeUserFromWorkspace;
        $this->addUserToWorkspace = $addUserToWorkspace;
    }

    public function index(Request $request): ViewContract
    {
        return view('users.index', [
            'users' => $request->user()->currentWorkspace->users,
            'invitations' => $request->user()->currentWorkspace->invitations,
        ]);
    }

    /**
     * Remove a user from the current workspace.
     *
     * @param int $userId
     *
     * @return RedirectResponse
     */
    public function destroy(Request $request, int $userId): RedirectResponse
    {
        /* @var $requestUser \App\Models\User */
        $requestUser = $request->user();

        if ($userId === $requestUser->id) {
            return redirect()
                ->back()
                ->with('error', __('You cannot remove yourself from your own workspace.'));
        }

        $workspace = $requestUser->currentWorkspace();

        if ($workspace->owner_id === $userId) {
            return redirect()
                ->back()
                ->with('error', __('You cannot remove principal owner from workspace.'));
        }

        $user = User::find($userId);

        $this->removeUserFromWorkspace->handle($user, $workspace);

        if ($user->workspaces()->count()==0){
            $user->delete();
        }

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                __(':user was removed from :workspace.', ['user' => $user->name, 'workspace' => $workspace->name])
            );
    }


    /**
     * @throws Exception
     */
    public function store(WorkspaceAddUserRequest $request): RedirectResponse
    {
        $workspace = $request->user()->currentWorkspace();

        $this->addUserToWorkspace->handle($workspace, $request->email, $request->role);

        return redirect()->route('users.index');
    }


}
