<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ShotWorks Model
 *
 * @property \App\Model\Table\FactoriesTable|\Cake\ORM\Association\BelongsTo $Factories
 * @property \App\Model\Table\ProductConditionParentsTable|\Cake\ORM\Association\BelongsTo $ProductConditionParents
 *
 * @method \App\Model\Entity\ShotWork get($primaryKey, $options = [])
 * @method \App\Model\Entity\ShotWork newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ShotWork[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ShotWork|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ShotWork|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ShotWork patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ShotWork[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ShotWork findOrCreate($search, callable $callback = null, $options = [])
 */
class ShotWorksTable extends Table
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

        $this->setTable('shot_works');
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
            ->dateTime('datetime_start')
            ->requirePresence('datetime_start', 'create')
            ->notEmpty('datetime_start');

        $validator
            ->dateTime('datetime_finish')
            ->requirePresence('datetime_finish', 'create')
            ->notEmpty('datetime_finish');

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
