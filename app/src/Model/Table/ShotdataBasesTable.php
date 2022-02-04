<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ShotdataBases Model
 *
 * @property |\Cake\ORM\Association\HasMany $ShotdataWarnings
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

        $this->hasMany('ShotdataWarnings', [
            'foreignKey' => 'shotdata_base_id'
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
            ->scalar('factory_code')
            ->maxLength('factory_code', 20)
            ->requirePresence('factory_code', 'create')
            ->notEmpty('factory_code');

        $validator
            ->integer('machine_num')
            ->requirePresence('machine_num', 'create')
            ->notEmpty('machine_num');

        $validator
            ->scalar('conf_a_b')
            ->maxLength('conf_a_b', 255)
            ->requirePresence('conf_a_b', 'create')
            ->notEmpty('conf_a_b');

        $validator
            ->dateTime('datetime')
            ->requirePresence('datetime', 'create')
            ->notEmpty('datetime');

        $validator
            ->numeric('stop_time')
            ->requirePresence('stop_time', 'create')
            ->notEmpty('stop_time');

        $validator
            ->integer('temp_outside')
            ->allowEmpty('temp_outside');

        $validator
            ->integer('temp_inside')
            ->allowEmpty('temp_inside');

        $validator
            ->integer('temp_water')
            ->allowEmpty('temp_water');

        $validator
            ->integer('analog1_ch1')
            ->allowEmpty('analog1_ch1');

        $validator
            ->integer('analog1_ch2')
            ->allowEmpty('analog1_ch2');

        $validator
            ->integer('analog1_ch3')
            ->allowEmpty('analog1_ch3');

        $validator
            ->integer('analog1_ch4')
            ->allowEmpty('analog1_ch4');

        $validator
            ->integer('valid_data_num')
            ->allowEmpty('valid_data_num');

        $validator
            ->integer('existence_stop')
            ->requirePresence('existence_stop', 'create')
            ->notEmpty('existence_stop');

        $validator
            ->integer('place_stop')
            ->requirePresence('place_stop', 'create')
            ->notEmpty('place_stop');

        $validator
            ->integer('existence_out_limit')
            ->requirePresence('existence_out_limit', 'create')
            ->notEmpty('existence_out_limit');

        $validator
            ->integer('place_out_limit')
            ->requirePresence('place_out_limit', 'create')
            ->notEmpty('place_out_limit');

        $validator
            ->integer('existence_change_standard_value')
            ->requirePresence('existence_change_standard_value', 'create')
            ->notEmpty('existence_change_standard_value');

        $validator
            ->numeric('value1_mode')
            ->allowEmpty('value1_mode');

        $validator
            ->numeric('value1_mean')
            ->allowEmpty('value1_mean');

        $validator
            ->numeric('value1_max')
            ->allowEmpty('value1_max');

        $validator
            ->numeric('value1_min')
            ->allowEmpty('value1_min');

        $validator
            ->numeric('value1_std')
            ->allowEmpty('value1_std');

        $validator
            ->numeric('value2_mode')
            ->allowEmpty('value2_mode');

        $validator
            ->numeric('value2_mean')
            ->allowEmpty('value2_mean');

        $validator
            ->numeric('value2_max')
            ->allowEmpty('value2_max');

        $validator
            ->numeric('value2_min')
            ->allowEmpty('value2_min');

        $validator
            ->numeric('value2_std')
            ->allowEmpty('value2_std');

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
}
