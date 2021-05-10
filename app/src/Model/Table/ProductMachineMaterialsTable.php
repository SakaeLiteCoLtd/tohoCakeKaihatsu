<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductMachineMaterials Model
 *
 * @property \App\Model\Table\ProductMaterialMachinesTable|\Cake\ORM\Association\BelongsTo $ProductMaterialMachines
 * @property \App\Model\Table\ProductMaterialLotNumbersTable|\Cake\ORM\Association\HasMany $ProductMaterialLotNumbers
 *
 * @method \App\Model\Entity\ProductMachineMaterial get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProductMachineMaterial newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProductMachineMaterial[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductMachineMaterial|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductMachineMaterial|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductMachineMaterial patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProductMachineMaterial[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductMachineMaterial findOrCreate($search, callable $callback = null, $options = [])
 */
class ProductMachineMaterialsTable extends Table
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

        $this->setTable('product_machine_materials');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('ProductMaterialMachines', [
            'foreignKey' => 'product_material_machine_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ProductMaterialLotNumbers', [
            'foreignKey' => 'product_machine_material_id'
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
            ->integer('material_number')
            ->requirePresence('material_number', 'create')
            ->notEmpty('material_number');

        $validator
            ->scalar('material_grade')
            ->maxLength('material_grade', 255)
            ->requirePresence('material_grade', 'create')
            ->notEmpty('material_grade');

        $validator
            ->scalar('material_maker')
            ->maxLength('material_maker', 255)
            ->requirePresence('material_maker', 'create')
            ->notEmpty('material_maker');

        $validator
            ->numeric('mixing_ratio')
            ->requirePresence('mixing_ratio', 'create')
            ->notEmpty('mixing_ratio');

        $validator
            ->numeric('dry_temp')
            ->requirePresence('dry_temp', 'create')
            ->notEmpty('dry_temp');

        $validator
            ->numeric('dry_hour')
            ->requirePresence('dry_hour', 'create')
            ->notEmpty('dry_hour');

        $validator
            ->numeric('recycled_mixing_ratio')
            ->requirePresence('recycled_mixing_ratio', 'create')
            ->notEmpty('recycled_mixing_ratio');

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
        $rules->add($rules->existsIn(['product_material_machine_id'], 'ProductMaterialMachines'));

        return $rules;
    }
}