<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if($this->route()->getActionMethod() == 'store')
        {
            $rules = [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8'
            ];
        }
        else if($this->route()->getActionMethod() == 'update')
        {
            $rules = [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,'.$this->user->id
            ];
        }
        else if($this->route()->getActionMethod() == 'updateAuth')
        {
            $rules = [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,'.auth()->user()->id
            ];
        }
        else if($this->route()->getActionMethod() == 'updatePassword' || $this->route()->getActionMethod() == 'updatePasswordAuth')
        {
            $rules = [
                'password' => 'required|min:8',
                'password_confirmation' => 'required|same:password'
            ];
        }
        else if($this->route()->getActionMethod() == 'setRole')
        {
            $rules = [
                'role' => 'required',
            ];
        }
        return $rules;
    }
}
