<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductConditonChildren Model
 *
 * @property \App\Model\Table\ProductMaterialMachinesTable|\Cake\ORM\Association\BelongsTo $ProductMaterialMachines
 *
 * @method \App\Model\Entity\ProductConditonChild get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProductConditonChild newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProductConditonChild[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductConditonChild|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductConditonChild|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductConditonChild patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProductConditonChild[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductConditonChild findOrCreate($search, callable $callback = null, $options = [])
 */
class ProductConditonChildrenTable extends Table
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

        $this->setTable('product_conditon_children');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('ProductMaterialMachines', [
            'foreignKey' => 'product_material_machine_id',
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
            ->integer('cylinder_number')
            ->requirePresence('cylinder_number', 'create')
            ->notEmpty('cylinder_number');

        $validator
            ->scalar('cylinder_name')
            ->maxLength('cylinder_name', 255)
            ->requirePresence('cylinder_name', 'create')
            ->notEmpty('cylinder_name');

        $validator
            ->numeric('temp_1')
            ->requirePresence('temp_1', 'create')
            ->notEmpty('temp_1');

        $validator
            ->numeric('temp_1_upper_limit')
            ->allowEmpty('temp_1_upper_limit');

        $validator
            ->numeric('temp_1_lower_limit')
            ->allowEmpty('temp_1_lower_limit');

        $validator
            ->numeric('temp_2')
            ->requirePresence('temp_2', 'create')
            ->notEmpty('temp_2');

        $validator
            ->numeric('temp_2_upper_limit')
            ->allowEmpty('temp_2_upper_limit');

        $validator
            ->numeric('temp_2_lower_limit')
            ->allowEmpty('temp_2_lower_limit');

        $validator
            ->numeric('temp_3')
            ->requirePresence('temp_3', 'create')
            ->notEmpty('temp_3');

        $validator
            ->numeric('temp_3_upper_limit')
            ->allowEmpty('temp_3_upper_limit');

        $validator
            ->numeric('temp_3_lower_limit')
            ->allowEmpty('temp_3_lower_limit');

        $validator
            ->numeric('temp_4')
            ->requirePresence('temp_4', 'create')
            ->notEmpty('temp_4');

        $validator
            ->numeric('temp_4_upper_limit')
            ->allowEmpty('temp_4_upper_limit');

        $validator
            ->numeric('temp_4_lower_limit')
            ->allowEmpty('temp_4_lower_limit');

        $validator
            ->numeric('temp_5')
            ->requirePresence('temp_5', 'create')
            ->notEmpty('temp_5');

        $validator
            ->numeric('temp_5_upper_limit')
            ->allowEmpty('temp_5_upper_limit');

        $validator
            ->numeric('temp_5_lower_limit')
            ->allowEmpty('temp_5_lower_limit');

        $validator
            ->numeric('temp_6')
            ->requirePresence('temp_6', 'create')
            ->notEmpty('temp_6');

        $validator
            ->numeric('temp_6_upper_limit')
            ->allowEmpty('temp_6_upper_limit');

        $validator
            ->numeric('temp_6_lower_limit')
            ->allowEmpty('temp_6_lower_limit');

        $validator
            ->numeric('temp_7')
            ->requirePresence('temp_7', 'create')
            ->notEmpty('temp_7');

        $validator
            ->numeric('temp_7_upper_limit')
            ->allowEmpty('temp_7_upper_limit');

        $validator
            ->numeric('temp_7_lower_limit')
            ->allowEmpty('temp_7_lower_limit');

        $validator
            ->numeric('extrude_roatation')
            ->requirePresence('extrude_roatation', 'create')
            ->notEmpty('extrude_roatation');

        $validator
            ->numeric('extrusion_load')
            ->requirePresence('extrusion_load', 'create')
            ->notEmpty('extrusion_load');

        $validator
            ->numeric('extrusion_upper_limit')
            ->allowEmpty('extrusion_upper_limit');

        $validator
            ->numeric('extrusion_lower_limit')
            ->allowEmpty('extrusion_lower_limit');

        $validator
            ->numeric('pickup_speed')
            ->requirePresence('pickup_speed', 'create')
            ->notEmpty('pickup_speed');

        $validator
            ->numeric('pickup_speed_upper_limit')
            ->allowEmpty('pickup_speed_upper_limit');

        $validator
            ->numeric('pickup_speed_lower_limit')
            ->allowEmpty('pickup_speed_lower_limit');

        $validator
            ->scalar('screw_mesh_1')
            ->maxLength('screw_mesh_1', 255)
            ->requirePresence('screw_mesh_1', 'create')
            ->notEmpty('screw_mesh_1');

        $validator
            ->scalar('screw_number_1')
            ->maxLength('screw_number_1', 255)
            ->requirePresence('screw_number_1', 'create')
            ->notEmpty('screw_number_1');

        $validator
            ->scalar('screw_mesh_2')
            ->maxLength('screw_mesh_2', 255)
            ->allowEmpty('screw_mesh_2');

        $validator
            ->scalar('screw_number_2')
            ->maxLength('screw_number_2', 255)
            ->allowEmpty('screw_number_2');

        $validator
            ->scalar('screw_mesh_3')
            ->maxLength('screw_mesh_3', 255)
            ->allowEmpty('screw_mesh_3');

        $validator
            ->scalar('screw_number_3')
            ->maxLength('screw_number_3', 255)
            ->allowEmpty('screw_number_3');

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
