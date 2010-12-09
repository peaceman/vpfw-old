<?php
class App_DataObject_User extends Vpfw_DataObject_Abstract {
    /**
     *
     * @var App_Validator_User
     */
    private $validator;

    public function  __construct(App_Validator_User $validator, $properties = null) {
        $this->validator = $validator;
        $this->data = array(
            'Id' => null,
            'Username' => null,
            'Password' => null,
            'Email' => null,
            'CreationTime' => null,
        );
        foreach ($this->data as &$val) {
            $val = array('val' => null, 'changed' => false);
        }
        parent::__construct($properties);
    }

    public function getUsername() {
        return $this->getData('Username');
    }

    public function getPassword() {
        return $this->getData('Password');
    }

    public function getEmail() {
        return $this->getData('Email');
    }

    public function getCreationTime() {
        return $this->getData('CreationTime');
    }

    public function setUsername($username, $validate = true) {
        if ($this->getUsername() != $username) {
            if (true == $validate) {
                $this->validator->validateUsername($username);
            }
            $this->setData('Username', $username);
        }
    }

    public function setPassword($password, $validate = true) {
        if ($this->getPassword() != $password) {
            if (true == $validate) {
                $this->validator->validatePassword($password);
            }
            $this->setData('Password', $password);
        }
    }

    public function setUsername($username, $validate = true) {
        if ($this->username != $username) {
            if (true == $validate) {
                $this->validator->validateUsername($username);
            }
            $this->username = $username;
            $this->sthChanged = true;
        }
    }

    /**
     * Die Validierung beschränkt sich bei dieser Methode auf die Überprüfung
     * der Hashlänge welche bei 32 Zeichen liegen sollte (MD5).
     * @param string $password Hier wird die bereits gehashte Version des Passwortes erwartet.
     * @param bool $validate
     */
    public function setPassword($password, $validate = true) {
        if ($this->password != $password) {
            if (true == $validate) {
                $this->validator->validatePassword($password);
            }
            $this->password = $password;
            $this->sthChanged = true;
        }
    }

    public function setEmail($email, $validate = true) {
        if ($this->email != $email) {
            if (true == $validate) {
                $this->validator->validateEmail($email);
            }
            $this->email = $email;
            $this->sthChanged = true;
        }
    }

    public function setCreationTime($creationTime, $validate = true) {
        if ($this->creationTime != $creationTime) {
            if (true == $validate) {
                $this->validator->validateCreationTime($creationTime);
            }
            $this->creationTime = $creationTime;
            $this->sthChanged = true;
        }
    }
    
}
