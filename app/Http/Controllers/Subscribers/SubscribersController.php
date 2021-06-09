<?php

declare(strict_types=1);

namespace App\Http\Controllers\Subscribers;

use App\Http\Middleware\OwnsCurrentWorkspace;
use Sendportal\Base\Repositories\Subscribers\SubscriberTenantRepositoryInterface;
use Sendportal\Base\Repositories\TagTenantRepository;
use Sendportal\Base\Http\Controllers\Subscribers\SubscribersController as BaseSubscribersController;
use Rap2hpoutre\FastExcel\FastExcel;
use Sendportal\Base\Facades\Sendportal;

class SubscribersController extends BaseSubscribersController
{    

    /** @var SubscriberTenantRepositoryInterface */
    private $subscriberRepo;

    public function __construct(SubscriberTenantRepositoryInterface $subscriberRepo, TagTenantRepository $tagRepo)
    {
        parent::__construct($subscriberRepo, $tagRepo);

        $this->subscriberRepo = $subscriberRepo;

        $this->middleware(OwnsCurrentWorkspace::class)->only([
            'create',
            'store',
            'edit',            
            'update',
            'destroy'
        ]);
    }

    /**
     * @return string|StreamedResponse
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws UnsupportedTypeException
     * @throws WriterNotOpenedException
     * @throws Exception
     */
    public function export()
    {
        $subscribers = $this->subscriberRepo->all(Sendportal::currentWorkspaceId(), 'id');

        if (!$subscribers->count()) {
            return redirect()->route('sendportal.subscribers.index')->withErrors(__('There are no subscribers to export'));
        }

        return (new FastExcel($subscribers))
            ->download(sprintf('subscribers-%s.csv', date('Y-m-d-H-m-s')), static function ($subscriber) {
                return [
                    'id' => $subscriber->id,
                    'hash' => $subscriber->hash,
                    'email' => $subscriber->email,
                    'first_name' => $subscriber->first_name,
                    'last_name' => $subscriber->last_name,
                    'text1' => $subscriber->text1,
                    'text2' => $subscriber->text2,
                    'created_at' => $subscriber->created_at,
                ];
            });
    }

}