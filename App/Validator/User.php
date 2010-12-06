<?php
class App_Validator_User {
    private $userModel;

    public function __construct(App_Model_User $userModel) {
        $this->userModel = $userModel;
    }

    /**
     * Überprüft den Usernamen auf seine Länge, seinen Syntax und ob er bereits
     * in der Datenbank existiert.
     *
     * @param string $username
     */
    public function validateUsername($username) {
        $usernameLen = strlen($username);
        if (3 > $usernameLen || 32 < $usernameLen) {
            throw new Vpfw_Exception_Validation('Der Username muss mindestens 3 und maximal 32 Zeichen lang sein');
        }

        if (0 == preg_match('/[A-Za-z0-9_-]+')) {
            throw new Vpfw_Exception_Validation('Der Username enthält ungültige Zeichen. Erlaubt sind A-Z, a-z, 0-9, _ und -');
        }

        if (true == $this->userModel->entryWithFieldValueExists('Username|s', $username)) {
            throw new Vpfw_Exception_Validation('Der Username ' . $username . ' wird bereits benutzt');
        }
    }

    /**
     * Überprüft den Passworthash auf seine maximale Länge von 32 Zeichen,
     * da es sich um einen MD5-Hash handeln sollte.
     *
     * @param string $password
     */
    public function validatePassword($password) {
        if (32 != strlen($password)) {
            throw new Vpfw_Exception_Validation('Ein MD5-Hash hat immer 32 Zeichen, hier muss was schief gelaufen sein');
        }
    }

    /**
     * Prüft ob die übergebene eMail-Adresse bereits in der Datenbank steht und
     * ob sie auch Syntaxmäßig einer eMail-Adresse entspricht.
     *
     * @param string $email
     */
    public function validateEmail($email) {
        if (0 == preg_match('/[a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/', $email)) {
            throw new Vpfw_Exception_Validation('Die eMail-Adresse "' . $email . '" entspricht nicht RFC 2822');
        }

        if (true == $this->userModel->entryWithFieldValueExists('Email|s', $email)) {
            throw new Vpfw_Exception_Validation('Die eMail-Adresse ' . $email  . ' wird bereits benutzt');
        }
    }

    /**
     * Da wir in der Tabelle einen vorzeichenlosen Datentyp zur Speicherung
     * benutzen sind Zeitpunkte vor 1970 nicht möglich.
     * 
     * @param mixed $creationTime
     */
    public function validateCreationTime($creationTime) {
        $creationTime = (int)$creationTime;
        if (0 > $creationTime) {
            throw new Vpfw_Exception_Validation('Es sind keine negativen Timestamps erlaubt');
        }
    }
}
