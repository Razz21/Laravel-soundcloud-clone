<?php

namespace App\Http\Requests\Track;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTracksRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Add custom fields to request before validation.
     *
     * @return mixed
     */

    protected function getValidatorInstance()
    {
        $data = $this->all();
        $data['slug'] = \Str::slug($data['title']);
        $this->getInputSource()->replace($data);

        return parent::getValidatorInstance();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            "file" => "required|file|mimetypes:audio/*|max:20480",
            'title' => 'required|string',
            'slug' => ['required', 'string', Rule::unique('tracks')->where(function ($query) {
                return $query->where('user_id', auth()->user()->id);
            })],
            // 'slug' => 'required|string|unique:tracks,slug,' . auth()->user()->id . ',user_id',
            'description' => 'nullable|string|max:2000',
            'cover' => 'nullable|image|dimensions:min_width=300,min_height=300|max:1024',
            'tags' => 'sometimes|array|max:5',
            'genre' => 'nullable|exists:genres,id',
        ];
    }
}
