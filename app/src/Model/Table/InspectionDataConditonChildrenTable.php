<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * InspectionDataConditonChildren Model
 *
 * @property \App\Model\Table\InspectionDataConditonParentsTable|\Cake\ORM\Association\BelongsTo $InspectionDataConditonParents
 * @property \App\Model\Table\ProductConditonChildrenTable|\Cake\ORM\Association\BelongsTo $ProductConditonChildren
 *
 * @method \App\Model\Entity\InspectionDataConditonChild get($primaryKey, $options = [])
 * @method \App\Model\Entity\InspectionDataConditonChild newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\InspectionDataConditonChild[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\InspectionDataConditonChild|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InspectionDataConditonChild|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InspectionDataConditonChild patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\InspectionDataConditonChild[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\InspectionDataConditonChild findOrCreate($search, callable $callback = null, $options = [])
 */
class InspectionDataConditonChildrenTable extends Table
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

        $this->setTable('inspection_data_conditon_children');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('InspectionDataConditonParents', [
            'foreignKey' => 'inspection_data_conditon_parent_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ProductConditonChildren', [
            'foreignKey' => 'product_conditon_child_id',
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
            ->numeric('inspection_temp_1')
            ->requirePresence('inspection_temp_1', 'create')
            ->notEmpty('inspection_temp_1');

        $validator
            ->numeric('inspection_temp_2')
            ->requirePresence('inspection_temp_2', 'create')
            ->notEmpty('inspection_temp_2');

        $validator
            ->numeric('inspection_temp_3')
            ->requirePresence('inspection_temp_3', 'create')
            ->notEmpty('inspection_temp_3');

        $validator
            ->numeric('inspection_temp_4')
            ->requirePresence('inspection_temp_4', 'create')
            ->notEmpty('inspection_temp_4');

        $validator
            ->numeric('inspection_temp_5')
            ->requirePresence('inspection_temp_5', 'create')
            ->notEmpty('inspection_temp_5');

        $validator
            ->numeric('inspection_temp_6')
            ->requirePresence('inspection_temp_6', 'create')
            ->notEmpty('inspection_temp_6');

        $validator
            ->numeric('inspection_temp_7')
            ->requirePresence('inspection_temp_7', 'create')
            ->notEmpty('inspection_temp_7');

        $validator
            ->numeric('inspection_extrude_roatation')
            ->allowEmpty('inspection_extrude_roatation');

        $validator
            ->numeric('inspection_extrusion_load')
            ->allowEmpty('inspection_extrusion_load');

        $validator
            ->numeric('inspection_pickup_speed')
            ->allowEmpty('inspection_pickup_speed');

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
        $rules->add($rules->existsIn(['inspection_data_conditon_parent_id'], 'InspectionDataConditonParents'));
        $rules->add($rules->existsIn(['product_conditon_child_id'], 'ProductConditonChildren'));

        return $rules;
    }
}
