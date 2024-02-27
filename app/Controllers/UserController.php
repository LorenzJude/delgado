<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\GenderModel;

class UserController extends BaseController {
    
    public function index(): string
    {
        return view('welcome_message');
    }

    public function adduser()
    {
        $data = array();
        helper('form');
        
        if($this->request == 'post') {
            $post = $this->request->getpost(["first_name", "middle_name", "last_name", "age", "gender_id", "email", "password"]);

            $rules = [
                'first_name' => ['label' => 'first_name', 'rules' => 'required'],
                'last_name' => ['label' => 'first_name', 'rules' => 'required'],
                'middle_name' => ['label' => 'middle_name', 'rules' => 'permit_empty'],
                'age' => ['label' => 'age', 'rules' => 'required'],
                'gender_id' => ['label' => 'gender_id', 'rules' => 'required'],
                'email' => ['label' => 'email', 'rules' => 'required |is_unique[user.email]'],
                'password' => ['label' => 'password', 'rules' => 'required'],
                'confirm_password' => ['label' => 'Confirm Password',
                'rules' => 'required_with[password]|matches[password]']
            ];
            
            if(! $this->validate($rules)) {
                $data['validation'] = $this->validator;
            }else {
                $post['password'] = sha1($post['password']);

                $userModel = new UserModel();
                $userModel->save($post);

                return redirect ('user/add');
                return 'User Succesfully Saved';
            }
        }
        
        //Fetch all values from genders table
        
        $genderModel = new GenderModel();
        $data ['genders'] = $genderModel->findAll();

        return view('user/add');
    
    }
}
