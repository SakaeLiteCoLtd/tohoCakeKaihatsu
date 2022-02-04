<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RelayLogs Model
 *
 * @property \App\Model\Table\MachineRelaysTable|\Cake\ORM\Association\BelongsTo $MachineRelays
 *
 * @method \App\Model\Entity\RelayLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\RelayLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\RelayLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\RelayLog|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RelayLog|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RelayLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\RelayLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\RelayLog findOrCreate($search, callable $callback = null, $options = [])
 */
class RelayLogsTable extends Table
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

        $this->setTable('relay_logs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('MachineRelays', [
            'foreignKey' => 'machine_relay_id',
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
            ->scalar('factory_code')
            ->maxLength('factory_code', 255)
            ->requirePresence('factory_code', 'create')
            ->notEmpty('factory_code');

        $validator
            ->integer('machine_num')
            ->requirePresence('machine_num', 'create')
            ->notEmpty('machine_num');

        $validator
            ->dateTime('datetime')
            ->requirePresence('datetime', 'create')
            ->notEmpty('datetime');

        $validator
            ->integer('status')
            ->requirePresence('status', 'create')
            ->notEmpty('status');

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
        $rules->add($rules->existsIn(['machine_relay_id'], 'MachineRelays'));

        return $rules;
    }
}
