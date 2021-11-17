<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Tanis Model
 *
 * @property \App\Model\Table\FactoriesTable|\Cake\ORM\Association\BelongsTo $Factories
 *
 * @method \App\Model\Entity\Tani get($primaryKey, $options = [])
 * @method \App\Model\Entity\Tani newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Tani[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Tani|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Tani|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Tani patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Tani[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Tani findOrCreate($search, callable $callback = null, $options = [])
 */
class TanisTable extends Table
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

        $this->setTable('tanis');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Factories', [
            'foreignKey' => 'factory_id',
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

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
        $rules->add($rules->existsIn(['factory_id'], 'Factories'));

        return $rules;
    }
}
