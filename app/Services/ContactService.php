<?php

namespace App\Services;

use App\Models\Contact;
use Illuminate\Support\Facades\Log;

class ContactService
{

    public function create(array $data): void
    {
        $model = new Contact();
        $model->amocrm_id = $data['id'];
        $model->phone = $data['phone'];
        $model->name = $data['name'];
        $model->email = $data['email'];
        $model->position = $data['position'];
        $model->responsible_user_id = $data['responsible_user_id'];

        if (isset($data['company_id'])) {
            $model->company_id = $data['company_id'];
        }
        $model->save();
    }

    public function compareFields(array $data): string
    {
        $contact = Contact::where('amocrm_id', $data['id'])->firstOrFail();
        $text = '';

        foreach (Contact::ATTRIBUTES_FOR_COMPARISON as $key => $value) {
            if ($contact->$key != $data[$key]) {
                $text .= " {$value} : " . $data[$key];
            }
        }

        $this->update($contact, $data);

        return $text;
    }

    public function update(Contact $contact, array $data): void
    {
        $contact->phone = $data['phone'];
        $contact->name = $data['name'];
        $contact->email = $data['email'];
        $contact->position = $data['position'];
        $contact->responsible_user_id = $data['responsible_user_id'];
        $contact->save();
    }

}
