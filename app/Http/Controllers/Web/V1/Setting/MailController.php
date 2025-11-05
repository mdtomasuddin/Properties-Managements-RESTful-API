<?php

namespace App\Http\Controllers\Web\V1\Setting;

use App\Http\Requests\Web\V1\Setting\Mail\StoreRequest;
use App\Services\Web\V1\Setting\MailService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MailController
{
    private MailService $mailService;

    /**
     * construct
     * @param \App\Services\Web\V1\Setting\MailService $mailService
     */
    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }


    /**
     * Summary of show
     * @return \Illuminate\Contracts\View\View
     */
    public function show(): View
    {
        return view('backend.layouts.settings.mail.index');
    }

    /**
     * store
     * @param \App\Http\Requests\Web\V1\Setting\Mail\StoreRequest $storeRequest
     * @return RedirectResponse
     */
    public function store(StoreRequest $storeRequest): RedirectResponse
    {
        try {
            $data = $storeRequest->only([
                'mail_mailer',
                'mail_host',
                'mail_port',
                'mail_username',
                'mail_password',
                'mail_encryption',
                'mail_address'
            ]);
            $this->mailService->updateMailConfig($data);
            return back()->with('t-success', 'Setting Saved');
        } catch (Exception $e) {
            Log::error('App\Http\Controllers\Web\V1\Setting\MailController:store', ['error' => $e->getMessage()]);
            return back()->with('t-error', 'Failed to update');
        }
    }
}
