<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ShotdataBases Model
 *
 * @property \App\Model\Table\FactoriesTable|\Cake\ORM\Association\BelongsTo $Factories
 * @property \App\Model\Table\ProductConditionParentsTable|\Cake\ORM\Association\BelongsTo $ProductConditionParents
 *
 * @method \App\Model\Entity\ShotdataBase get($primaryKey, $options = [])
 * @method \App\Model\Entity\ShotdataBase newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ShotdataBase[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ShotdataBase|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ShotdataBase|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ShotdataBase patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ShotdataBase[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ShotdataBase findOrCreate($search, callable $callback = null, $options = [])
 */
class ShotdataBasesTable extends Table
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

        $this->setTable('shotdata_bases');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Factories', [
            'foreignKey' => 'factory_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ProductConditionParents', [
            'foreignKey' => 'product_condition_parent_id'
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
            ->dateTime('datetime')
            ->requirePresence('datetime', 'create')
            ->notEmpty('datetime');

        $validator
            ->integer('valid_data_num')
            ->requirePresence('valid_data_num', 'create')
            ->notEmpty('valid_data_num');

        $validator
            ->numeric('stop_time')
            ->requirePresence('stop_time', 'create')
            ->notEmpty('stop_time');

        $validator
            ->integer('extrusion_switch_conf')
            ->requirePresence('extrusion_switch_conf', 'create')
            ->notEmpty('extrusion_switch_conf');

        $validator
            ->integer('pickup_switch_conf')
            ->requirePresence('pickup_switch_conf', 'create')
            ->notEmpty('pickup_switch_conf');

        $validator
            ->numeric('value_mode')
            ->allowEmpty('value_mode');

        $validator
            ->numeric('value_ave')
            ->allowEmpty('value_ave');

        $validator
            ->numeric('value_max')
            ->allowEmpty('value_max');

        $validator
            ->numeric('value_min')
            ->allowEmpty('value_min');

        $validator
            ->numeric('value_std')
            ->requirePresence('value_std', 'create')
            ->notEmpty('value_std');

        $validator
            ->integer('status_sencer')
            ->requirePresence('status_sencer', 'create')
            ->notEmpty('status_sencer');

        $validator
            ->integer('delete_flag')
            ->requirePresence('delete_flag', 'create')
            ->notEmpty('delete_flag');

        $validator
            ->dateTime('created_at')
            ->requirePresence('created_at', 'create')
            ->notEmpty('created_at');

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
        $rules->add($rules->existsIn(['factory_id'], 'Factories'));
        $rules->add($rules->existsIn(['product_condition_parent_id'], 'ProductConditionParents'));

        return $rules;
    }
}
