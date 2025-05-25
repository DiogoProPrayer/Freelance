<?php

    class User {
        public int $user_id;
        public string $name;
        public string $username;
        public string $email;
        public string $password;
        public string $user_status;
        public string $profileImage;
        public ?string $country;
        public ?string $phoneNumber;
        public ?int $isAdmin;

        public function __construct(array $data) {
            $this->user_id = (int)$data['id'];
            $this->name = $data['name'];
            $this->username = $data['username'];
            $this->email = $data['email'];
            $this->password = $data['passwordHash'];
            $this->user_status = $data['userStatus'];
            $this->profileImage = $data['profileImage'] ?? '../images/default_user.jpg';
            $this->country = $data['country'];
            $this->phoneNumber = $data['phoneNumber'];
            $this->isAdmin = $data['isAdmin'] ?? 0;
        }

        public function getUserStatus(){
            return $this->user_status;
        }

        public function getName(){
            return $this->name;
        }

        public function getuserId(){
            return $this->us_id;
        }

        public function getUsername(){
            return $this->username;
        }

        public function getProfileImage(){
            return $this->profileImage;
        }

        public function getCountry(){
            return $this->country;
        }
        public function getEmail(){
            return $this->email;
        }
        public function getPhoneNumber(){
            return $this->phoneNumber;
        }

        public function getIsAdmin(){
            return $this->isAdmin;
        }
    }


?>