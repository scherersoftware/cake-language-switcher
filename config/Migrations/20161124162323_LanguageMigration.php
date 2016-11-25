<?php
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Migrations\AbstractMigration;

class LanguageMigration extends AbstractMigration
{

    protected $_model = null;

    public function init()
    {
        $this->_model = TableRegistry::get(Configure::read('LanguageSwitcher.model'));
    }

    public function up()
    {
        $this->table($this->_model->table())
            ->addColumn(Configure::read('LanguageSwitcher.field'), 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->update();
    }

    public function down()
    {
        $this->table($this->_model->table())
            ->removeColumn(Configure::read('LanguageSwitcher.field'))
            ->update();
    }
}
