<?php

namespace App\Http\Requests\Audio;

use Illuminate\Foundation\Http\FormRequest;

class FormAudioRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'mimes:mp3',  
                'max:131072', 
            ],
            'title' =>['string']
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Selecione um arquivo de áudio.',
            'file.mimes'    => 'O arquivo deve estar no formato MP3.',
            'file.max'      => 'O arquivo não pode ultrapassar 128MB.',
            'title' => 'O titulo deve ser válido!'
        ];
    }
}