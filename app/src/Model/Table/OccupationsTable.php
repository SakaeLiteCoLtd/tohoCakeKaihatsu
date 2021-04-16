<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Occupations Model
 *
 * @property \App\Model\Table\OfficesTable|\Cake\ORM\Association\BelongsTo $Offices
 * @property \App\Model\Table\StaffsTable|\Cake\ORM\Association\HasMany $Staffs
 *
 * @method \App\Model\Entity\Occupation get($primaryKey, $options = [])
 * @method \App\Model\Entity\Occupation newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Occupation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Occupation|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Occupation|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Occupation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Occupation[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Occupation findOrCreate($search, callable $callback = null, $options = [])
 */
class OccupationsTable extends Table
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

        $this->setTable('occupations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Offices', [
            'foreignKey' => 'office_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Staffs', [
            'foreignKey' => 'occupation_id'
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
            ->scalar('occupation')
            ->maxLength('occupation', 40)
            ->requirePresence('occupation', 'create')
            ->notEmpty('occupation');

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
        $rules->add($rules->existsIn(['office_id'], 'Offices'));

        return $rules;
    }
}
