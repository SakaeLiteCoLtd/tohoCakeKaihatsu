<?php
use Migrations\AbstractMigration;

class RemoveMachineNumFromProductConditionParents extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
/*
    public function change()
    {
        $table = $this->table('product_condition_parents');
        $table->removeColumn('machine_num');
        $table->update();
    }
*/
    public function up()
{
    $table = $this->table('product_condition_parents');
    $table->removeColumn('machine_num');
    $table->update();
}

public function down()
{
  $table = $this->table('product_condition_parents');
  $table->addColumn('machine_num', 'integer', [
    'default' => null,
    'limit' => 2,
    'null' => true,
  ]);
  $table->update();
}
}
