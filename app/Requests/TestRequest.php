<?php

namespace App\Requests;

use Asgard\system\Request;

class TestRequest extends Request
{
    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->validate($this->rules());
    }
    public function rules(): array
    {
        return [
            'name' => 'required',
        ];
    }
}