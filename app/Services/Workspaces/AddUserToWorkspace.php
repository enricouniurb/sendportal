<?php

declare(strict_types=1);

namespace App\Services\Workspaces;

use App\Models\Workspace;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AddUserToWorkspace
{
    /** @var AddWorkspaceMember */
    protected $addWorkspaceMember;

    public function __construct(AddWorkspaceMember $addWorkspaceMember)
    {
        $this->addWorkspaceMember = $addWorkspaceMember;
    }

      
    public function handle(Workspace $workspace, $email, $role): void
    {
        DB::transaction(function () use ($workspace, $email, $role) {
            $user = User::where('email', $email)->first();

            if ($user==null){
            /** @var User $user */
                $user = User::create([
                    'name' => substr($email, 0, strrpos($email, '@')),
                    'email' => $email,
                    'password' => Hash::make(Str::random(8)),
                ]);
            }
    
            $this->addWorkspaceMember->handle($workspace, $user, $role);

        });
    }
}
