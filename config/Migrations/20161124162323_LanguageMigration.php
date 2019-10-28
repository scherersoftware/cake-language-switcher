<?php
declare(strict_types = 1);

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Migrations\AbstractMigration;

class LanguageMigration extends AbstractMigration
{

    protected $_model = null;
    protected $_config = null;

    public function init()
    {
        $this->_config = Hash::merge([
            'model' => 'Users',
            'field' => 'language'
        ], Configure::read('LanguageSwitcher'));

        $this->_model = TableRegistry::getTableLocator()->get($this->_config['model']);
    }

    public function up()
    {
        $this->table($this->_model->table())
            ->addColumn($this->_config['field'], 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->update();
    }

    public function down()
    {
        $this->table($this->_model->table())
            ->removeColumn($this->_config['field'])
            ->update();
    }
}
