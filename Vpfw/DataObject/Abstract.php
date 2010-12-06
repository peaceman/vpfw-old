<?php
abstract class Vpfw_DataObject_Abstract implements Vpfw_DataObject_Interface {
    /**
     * Beinhaltet den Inhalt des Objektes, dieses Array muss von den Kindklassen
     * mit Schlüsseln befüllt werden.
     * @var array
     */
    protected $data = array();

    /**
     * @var bool
     */
    protected $sthChanged = false;

    /**
     * @return bool
     */
    public function isSomethingChanged() {
        return $this->sthChanged;
    }

    /**
     * @param bool $state
     */
    public function setSomethingChanged($state) {
        $this->sthChanged = $state;
    }
    
    /**
     * @throws Vpfw_Exception_Logical
     * @param int $which
     * @return array
     */
    public function exportData($which = Vpfw_DataObject_Interface::WITHOUT_ID) {
        $returnArray = array();
        foreach ($this->data as $key => $details) {
            if (false == is_null($details['value'])) {
                switch ($which) {
                    case Vpfw_DataObject_Interface::WITHOUT_ID:
                        if ('Id' != $key) {
                            $returnArray[$key] = $details['value'];
                        }
                        break;
                    case Vpfw_DataObject_Interface::ALL:
                        $returnArray[$key] = $details['value'];
                        break;
                    case Vpfw_DataObject_Interface::CHANGED:
                        if (true == $details['changed']) {
                            $returnArray[$key] = $details['value'];
                        }
                        break;
                    default:
                        throw new Vpfw_Exception_Logical('Keine Ahnung was du von mir willst!?');
                        break;
                }
            }
        }
        return $returnArray;
    }

    /**
     * @throws Vpfw_Exception_Logical
     * @param string $name
     * @return mixed
     */
    protected function getData($name) {
        $this->checkDataKey($name);
        return $this->data[$name]['val'];
    }

    /**
     * @throws Vpfw_Exception_Logical
     * @param string $name
     * @param string $value
     * @param bool $setChangeFlag
     */
    protected function setData($name, $value, $setChangeFlag = true) {
        $this->checkDataKey($name);
        $this->data[$name]['val'] = $value;
        if (true == $setChangeFlag) {
            $this->data[$name]['changed'] = true;
        }
    }

    /**
     * @throws Vpfw_Exception_Logical
     * @param string $keyName
     */
    private function checkDataKey($keyName) {
        if (false == array_key_exists($keyName, $this->data)) {
            throw new Vpfw_Exception_Logical('Ein Attribut mit dem Namen ' . $keyName . ' ist in diesem DataObject nicht bekannt');
        }
        if (false == array_keys_exists(array('val', 'changed'), $this->data[$keyName])) {
            throw new Vpfw_Exception_Logical('Das data Array in ' . get_called_class() . ' besitzt nicht die erwartete Struktur');
        }
    }

    /**
     * @throws Vpfw_Exception_Logical
     * @param int $id
     * @param bool $validate
     */
    public function setId($id, $validate = true) {
        if ($this->getId() != $id) {
            if (true == $validate) {
                throw new Vpfw_Exception_Logical('Die Id eines DataObjects darf nicht manuell gesetzt werden');
            } else {
                $this->setData('Id', $id);
            }
        }
    }
}
