<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductMaterialMachines Model
 *
 * @property |\Cake\ORM\Association\BelongsTo $ProductConditionParents
 * @property \App\Model\Table\ProductMachineMaterialsTable|\Cake\ORM\Association\HasMany $ProductMachineMaterials
 *
 * @method \App\Model\Entity\ProductMaterialMachine get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProductMaterialMachine newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProductMaterialMachine[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductMaterialMachine|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductMaterialMachine|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductMaterialMachine patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProductMaterialMachine[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductMaterialMachine findOrCreate($search, callable $callback = null, $options = [])
 */
class ProductMaterialMachinesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('product_material_machines');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('ProductConditionParents', [
            'foreignKey' => 'product_condition_parent_id',
            'joinType' => 'INNER'
        ]);

        $this->hasMany('ProductMachineMaterials', [
            'foreignKey' => 'product_material_machine_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->integer('cylinder_number')
            ->requirePresence('cylinder_number', 'create')
            ->notEmpty('cylinder_number');

        $validator
            ->scalar('cylinder_name')
            ->maxLength('cylinder_name', 255)
            ->requirePresence('cylinder_name', 'create')
            ->notEmpty('cylinder_name');

        $validator
            ->integer('delete_flag')
            ->requirePresence('delete_flag', 'create')
            ->notEmpty('delete_flag');

        $validator
            ->dateTime('created_at')
            ->requirePresence('created_at', 'create')
            ->notEmpty('created_at');

        $validator
            ->integer('created_staff')
            ->requirePresence('created_staff', 'create')
            ->notEmpty('created_staff');

        $validator
            ->dateTime('updated_at')
            ->allowEmpty('updated_at');

        $validator
            ->integer('updated_staff')
            ->allowEmpty('updated_staff');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
  //      $rules->add($rules->existsIn(['product_condition_parent_id'], 'ProductConditionParents'));

        return $rules;
    }
}
