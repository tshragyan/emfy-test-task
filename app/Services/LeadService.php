<?php

namespace App\Services;

use App\Models\Lead;

class LeadService
{

    public function create(array $data): void
    {
        $model = new Lead();
        $model->amocrm_id = $data['id'];
        $model->name = $data['name'];
        $model->price = $data['price'];
        $model->status_id = $data['status_id'];
        $model->responsible_user_id = $data['responsible_user_id'];

        if (isset($data['company_id'])) {
            $model->company_id = $data['company_id'];
        }

        $model->save();
    }

    public function compareFields(array $data): string
    {
        $contact = Lead::where('amocrm_id', $data['id'])->firstOrFail();
        $text = '';

        foreach (Lead::ATTRIBUTES_FOR_COMPARISON as $key => $value) {
            if ($contact->$key != $data[$key]) {
                $text .= " {$value} : " . $data[$key];
            }
        }

        $this->update($contact, $data);

        return $text;
    }

    public function update(Lead $lead, array $data): void
    {
        $lead->status_id = $data['status_id'];
        $lead->name = $data['name'];
        $lead->price = $data['price'];
        $lead->save();
    }

}
