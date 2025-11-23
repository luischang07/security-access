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
      'nombre' => [
        'required',
        'string',
        'max:255',
      ],
      'apellido' => [
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
        'unique:users,correo',
      ],
      'password' => [
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
      'nombre.required' => 'El nombre es obligatorio.',
      'nombre.max' => 'El nombre no puede exceder los 255 caracteres.',
      'apellido.required' => 'El apellido es obligatorio.',
      'apellido.max' => 'El apellido no puede exceder los 255 caracteres.',
      'email.required' => 'El correo electrónico es obligatorio.',
      'email.email' => 'El correo electrónico debe tener un formato válido.',
      'email.regex' => 'El correo no cumple con el patrón requerido.',
      'email.max' => 'El correo electrónico no puede exceder los 255 caracteres.',
      'email.unique' => 'Este correo electrónico ya está registrado.',
      'password.required' => 'La contraseña es obligatoria.',
      'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
      'password.regex' => 'La contraseña debe tener al menos una minúscula, una mayúscula, un número y un símbolo.',
      'password.confirmed' => 'La confirmación de la contraseña no coincide.',
    ];
  }
}
