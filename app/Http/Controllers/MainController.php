<?php

namespace App\Http\Controllers;

use App\Http\Requests\AmoCrmAuthRequest;
use App\Services\AmoCrmService;
use Illuminate\Http\Request;

class MainController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new AmoCrmService();
    }

    public function index(AmoCrmAuthRequest $request)
    {
        $this->service->setConnection($request->validated()['code']);
    }
    public function leadAdded(Request $request)
    {
        $this->service->addNoteToCreatedLead($request->input()['leads']['add'][0]['id']);
    }

    public function leadChanged(Request $request)
    {
        $this->service->addNoteToChangedLead($request->input()['leads']['update'][0]['id']);
    }

    public function contactAdded(Request $request)
    {
        $this->service->addNoteToCreatedContact($request->input()['contacts']['add'][0]['id']);
    }

    public function contactChanged(Request $request)
    {
        $this->service->addNoteToChangedContact($request->input()['contacts']['update'][0]['id']);
    }
}
