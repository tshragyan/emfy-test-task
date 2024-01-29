<?php

namespace App\Services;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\CompanyModel;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\LeadModel;
use AmoCRM\Models\NoteType\CommonNote;
use App\Clients\AmoCrmClient;
use App\Models\AmoCrmToken;
use App\Models\Company;
use App\Models\Contact;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class AmoCrmService
{
     private AmoCRMApiClient $client;

    public function __construct()
    {
        $this->client = AmoCrmClient::getClient();
    }

    public function setConnection($code)
    {
        $token = AmoCrmClient::setConnection($code);
        $amoCrmToken = AmoCrmToken::query()->first();

        if (!$amoCrmToken) {
            $amoCrmToken = new AmoCrmToken();
        }
        $amoCrmToken->token = json_encode($token->jsonSerialize());
        $amoCrmToken->save();
    }

    public function addNoteToCreatedContact($id)
    {
        $contact = $this->client->contacts()->getOne($id);
        $responsibleUserId = $contact->getResponsibleUserId();
        $created_at = Carbon::createFromTimestamp($contact->getCreatedAt())
            ->setTimezone('Europe/Moscow')->format('H:i:s');

        $responsibleUser = $this->client->users()->getOne($responsibleUserId);

        $text = "
                Название : {$contact->getName()} {$contact->getLastName()}
                Ответственный : {$responsibleUser->getName()}
                Время добавления : {$created_at}
            ";

        $commonNote = (new CommonNote())
            ->setEntityId($id)
            ->setText($text);

        $this->client->notes(EntityTypesInterface::CONTACTS)->addOne($commonNote);

        $contactData = $this->getDataForContact($contact);
        $contactService = new ContactService();
        $contactService->create($contactData);

        if ($contact->getCompany()) {
            $company = $this->client->companies()->getOne($contact->getCompany()->getId());
            $companyData = $this->getDataForCompany($company);
            $companyService = new CompanyService();
            $companyService->create($companyData);
        }
    }

    public function addNoteToChangedContact($id)
    {
        $contact = $this->client->contacts()->getOne($id);

        $contactData = $this->getDataForContact($contact);
        $contactService = new ContactService();
        $text = $contactService->compareFields($contactData);

        if ($contact->getCompany()->getId()) {
            $company = $this->client->companies()->getOne($contact->getCompany()->getId());
            $companyData = $this->getDataForCompany($company);
            $companyService = new CompanyService();
            $text .= $companyService->compareFields($companyData);
        }

        $commonNote = (new CommonNote())
            ->setEntityId($id)
            ->setText($text);

        $this->client->notes(EntityTypesInterface::CONTACTS)->addOne($commonNote);
    }

    public function addNoteToCreatedLead($id)
    {
        $lead = $this->client->leads()->getOne($id);
        $leadData = $this->getDataForLead($lead);
        $company = $lead->getCompany();
        $leadService = new LeadService();

        if ($company) {
            $companyService = new CompanyService();
            $companyData = $this->getDataForCompany($company);
            if (Company::where('amocrm_id', $company->getId())->count()) {
                $companyService->compareFields($companyData);
            } else {
                $companyService->create($companyData);
            }
        }

        $responsibleUserId = $lead->getResponsibleUserId();
        $created_at = Carbon::createFromTimestamp($lead->getCreatedAt())
            ->setTimezone('Europe/Moscow')->format('H:i:s');

        $responsibleUser = $this->client->users()->getOne($responsibleUserId);

        $text = "
                Название : {$lead->getName()}
                Ответственный : {$responsibleUser->getName()}
                Время добавления : {$created_at}
            ";

        $commonNote = (new CommonNote())
            ->setEntityId($id)
            ->setText($text);

        $this->client->notes(EntityTypesInterface::LEADS)->addOne($commonNote);

        $leadService->create($leadData);

    }

    public function addNoteToChangedLead($id)
    {
        $lead = $this->client->leads()->getOne($id);

        $leadData = $this->getDataForLead($lead);
        $leadService = new LeadService();
        $text = $leadService->compareFields($leadData);

        if ($lead->getCompany()) {
            $company = $this->client->companies()->getOne($lead->getCompany()->getId());
            $companyData = $this->getDataForCompany($company);
            $companyService = new CompanyService();
            $text .= $companyService->compareFields($companyData);
        }

        $commonNote = (new CommonNote())
            ->setEntityId($id)
            ->setText($text);

        $this->client->notes(EntityTypesInterface::LEADS)->addOne($commonNote);

    }

    private function getDataForContact(ContactModel $contact): array
    {
        $customFields = $contact->getCustomFieldsValues();

        $data =  [
            'id' => $contact->getId(),
            'name' => $contact->getName(),
            'phone' => $customFields->getBy('fieldCode', 'PHONE')->getValues()->first()->getValue(),
            'email' => $customFields->getBy('fieldCode', 'EMAIL')->getValues()->first()->getValue(),
            'position' => $customFields->getBy('fieldCode', 'POSITION')->getValues()->first()->getValue(),
            'responsible_user_id' => $contact->getResponsibleUserId()
        ];

        if ($contact->getCompany()) {
            $data['company_id'] = $contact->getCompany()->getId();
        }

        return $data;
    }

    private function getDataForCompany(CompanyModel $company): array
    {
        $customFields = $company->getCustomFieldsValues();

        return [
            'id' => $company->getId(),
            'name' => $company->getName(),
            'phone' => $customFields->getBy('fieldCode', 'PHONE')->getValues()->first()->getValue(),
            'email' => $customFields->getBy('fieldCode', 'EMAIL')->getValues()->first()->getValue(),
            'web' => $customFields->getBy('fieldCode', 'WEB')->getValues()->first()->getValue(),
            'address' => $customFields->getBy('fieldCode', 'ADDRESS')->getValues()->first()->getValue(),
        ];
    }

    private function getDataForLead(LeadModel $lead): array
    {
        $data = [
            'id' => $lead->getId(),
            'name' => $lead->getName(),
            'status_id' => $lead->getStatusId(),
            'price' => $lead->getPrice(),
            'responsible_user_id' => $lead->getResponsibleUserId(),
        ];

        if ($lead->getCompany()) {
            $data['company_id'] = $lead->getCompany()->getId();
        }

        return $data;
    }


}
