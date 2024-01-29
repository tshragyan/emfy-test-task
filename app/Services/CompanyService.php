<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Support\Facades\Log;

class CompanyService
{
    public function create($data)
    {
        $model = new Company();
        $model->amocrm_id = $data['id'];
        $model->email = $data['email'];
        $model->name = $data['name'];
        $model->phone = $data['phone'];
        $model->web = $data['web'];
        $model->address = $data['address'];
        $model->save();
    }

    public function compareFields(array $data): string
    {
        $company = Company::where('amocrm_id', $data['id'])->first();

        $text = '';

        if (!$company) {
            $text = 'Added Company';
            $this->create($data);

            return $text;
        }

        foreach (Company::ATTRIBUTES_FOR_COMPARISON as $key => $value) {
            if ($company->$key != $data[$key]) {
                $text .= " {$value} : " . $data[$key];
            }
        }

        $this->update($company, $data);

        return $text;
    }

    public function update(Company $company, array $data): void
    {
        $company->name = $data['name'];
        $company->email = $data['email'];
        $company->phone = $data['phone'];
        $company->web = $data['web'];
        $company->address = $data['address'];
        $company->save();
    }
}
