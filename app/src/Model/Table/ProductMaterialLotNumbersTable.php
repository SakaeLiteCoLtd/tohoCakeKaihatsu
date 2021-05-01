<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductMaterialLotNumbers Model
 *
 * @property \App\Model\Table\ProductMachineMaterialsTable|\Cake\ORM\Association\BelongsTo $ProductMachineMaterials
 * @property \App\Model\Table\StaffsTable|\Cake\ORM\Association\BelongsTo $Staffs
 *
 * @method \App\Model\Entity\ProductMaterialLotNumber get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProductMaterialLotNumber newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProductMaterialLotNumber[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductMaterialLotNumber|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductMaterialLotNumber|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductMaterialLotNumber patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProductMaterialLotNumber[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductMaterialLotNumber findOrCreate($search, callable $callback = null, $options = [])
 */
class ProductMaterialLotNumbersTable extends Table
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

        $this->setTable('product_material_lot_numbers');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('ProductMachineMaterials', [
            'foreignKey' => 'product_machine_material_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Staffs', [
            'foreignKey' => 'staff_id',
            'joinType' => 'INNER'
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
            ->dateTime('datetme')
            ->requirePresence('datetme', 'create')
            ->notEmpty('datetme');

        $validator
            ->scalar('lot_number')
            ->maxLength('lot_number', 255)
            ->requirePresence('lot_number', 'create')
            ->notEmpty('lot_number');

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
        $rules->add($rules->existsIn(['product_machine_material_id'], 'ProductMachineMaterials'));
        $rules->add($rules->existsIn(['staff_id'], 'Staffs'));

        return $rules;
    }
}
