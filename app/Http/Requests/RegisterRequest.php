<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'name' => [
        'required',
        'string',
        'max:255',
      ],
      'email' => [
        'required',
        'string',
        'email',
        'regex:/^[\w\.-]+@[\w\.-]+\.[\w]{2,4}$/i',
        'max:255',
        'unique:users',
      ],
      'nip' => [
        'required',
        'string',
        'min:8',
        'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
        'confirmed',
      ],
    ];
  }

  public function messages(): array
  {
    return [
      'name.required' => 'El nombre es obligatorio.',
      'name.max' => 'El nombre no puede exceder los 255 caracteres.',
      'email.required' => 'El correo electrónico es obligatorio.',
      'email.email' => 'El correo electrónico debe tener un formato válido.',
      'email.regex' => 'El correo no cumple con el patrón requerido.',
      'email.max' => 'El correo electrónico no puede exceder los 255 caracteres.',
      'email.unique' => 'Este correo electrónico ya está registrado.',
      'nip.required' => 'El NIP es obligatorio.',
      'nip.min' => 'El NIP debe tener al menos 8 caracteres.',
      'nip.regex' => 'El NIP debe tener al menos una minúscula, una mayúscula, un número y un símbolo.',
      'nip.confirmed' => 'La confirmación del NIP no coincide.',
    ];
  }
}
