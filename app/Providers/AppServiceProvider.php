<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\ApiToken;
use App\Http\Livewire\Setup;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use RuntimeException;
use Sendportal\Base\Facades\Sendportal;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('Sendportal\Base\Http\Controllers\EmailServices\EmailServicesController', 'App\Http\Controllers\EmailServices\EmailServicesController');
        $this->app->bind('Sendportal\Base\Http\Controllers\Subscribers\SubscribersController', 'App\Http\Controllers\Subscribers\SubscribersController');
        $this->app->bind('Sendportal\Base\Http\Controllers\Subscribers\SubscribersImportController', 'App\Http\Controllers\Subscribers\SubscribersImportController');
        $this->app->bind('Sendportal\Base\Http\Controllers\Tags\TagsController', 'App\Http\Controllers\Tags\TagsController');
        $this->app->bind('Sendportal\Base\Http\Controllers\Campaigns\CampaignsController', 'App\Http\Controllers\Campaigns\CampaignsController');
        $this->app->bind('Sendportal\Base\Http\Controllers\MessagesController', 'App\Http\Controllers\MessagesController');

        $this->app->bind('Sendportal\Base\Models\Subscriber', 'App\Models\Subscriber');
        $this->app->bind('Sendportal\Base\Models\Message', 'App\Models\Message');
        $this->app->bind('Sendportal\Base\Services\Content\MergeContentService', 'App\Services\Content\MergeContentService');
        $this->app->bind('Sendportal\Base\Http\Requests\SubscriberRequest', 'App\Http\Requests\SubscriberRequest');
        $this->app->bind('Sendportal\Base\Repositories\Subscribers\MySqlSubscriberTenantRepository', 'App\Repositories\MySqlSubscriberTenantRepository');
    }

    public function boot(): void
    {
        Paginator::useBootstrap();

        Sendportal::setCurrentWorkspaceIdResolver(
            static function () {
                /** @var User $user */
                $user = auth()->user();
                $request = request();
                $workspaceId = null;

                if ($user && $user->currentWorkspaceId()) {
                    $workspaceId = $user->currentWorkspaceId();
                } else if ($request && (($apiToken = $request->bearerToken()) || ($apiToken = $request->get('api_token')))) {
                    $workspaceId = ApiToken::resolveWorkspaceId($apiToken);
                }

                if (! $workspaceId) {
                    throw new RuntimeException("Current Workspace ID Resolver must not return a null value.");
                }

                return $workspaceId;
            }
        );

        Sendportal::setSidebarHtmlContentResolver(
            static function () {
                return view('layouts.sidebar.manageUsersMenuItem')->render();
            }
        );

        Sendportal::setHeaderHtmlContentResolver(
            static function () {
                return view('layouts.header.userManagementHeader')->render();
            }
        );

        Livewire::component('setup', Setup::class);
    }
}
