<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MachineRelays Model
 *
 * @property \App\Model\Table\RelayLogsTable|\Cake\ORM\Association\HasMany $RelayLogs
 *
 * @method \App\Model\Entity\MachineRelay get($primaryKey, $options = [])
 * @method \App\Model\Entity\MachineRelay newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MachineRelay[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MachineRelay|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MachineRelay|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MachineRelay patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MachineRelay[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MachineRelay findOrCreate($search, callable $callback = null, $options = [])
 */
class MachineRelaysTable extends Table
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

        $this->setTable('machine_relays');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('RelayLogs', [
            'foreignKey' => 'machine_relay_id'
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
            ->scalar('relay_code')
            ->maxLength('relay_code', 255)
            ->requirePresence('relay_code', 'create')
            ->notEmpty('relay_code');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('bik')
            ->maxLength('bik', 255)
            ->allowEmpty('bik');

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
