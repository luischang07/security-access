<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'correo' => [
        'required',
        'email',
        'regex:/^[\w\.-]+@[\w\.-]+\.[\w]{2,4}$/i',
        'max:255',
      ],
      'password' => [
        'required',
        'string',
        'min:8',
        'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
      ],
    ];
  }

  public function messages(): array
  {
    return [
      'correo.required' => 'El correo electrónico es obligatorio.',
      'correo.email' => 'El correo electrónico debe tener un formato válido.',
      'correo.regex' => 'El correo no cumple con el patrón requerido.',
      'password.required' => 'La contraseña es obligatoria.',
      'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
      'password.regex' => 'La contraseña debe tener al menos una mayúscula, un número y un símbolo.',
    ];
  }
}
