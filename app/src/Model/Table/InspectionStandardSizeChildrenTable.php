<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * InspectionStandardSizeChildren Model
 *
 * @property \App\Model\Table\InspectionStandardSizeParentsTable|\Cake\ORM\Association\BelongsTo $InspectionStandardSizeParents
 * @property \App\Model\Table\InspectionDataResultChildrenTable|\Cake\ORM\Association\HasMany $InspectionDataResultChildren
 *
 * @method \App\Model\Entity\InspectionStandardSizeChild get($primaryKey, $options = [])
 * @method \App\Model\Entity\InspectionStandardSizeChild newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\InspectionStandardSizeChild[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\InspectionStandardSizeChild|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InspectionStandardSizeChild|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InspectionStandardSizeChild patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\InspectionStandardSizeChild[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\InspectionStandardSizeChild findOrCreate($search, callable $callback = null, $options = [])
 */
class InspectionStandardSizeChildrenTable extends Table
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

        $this->setTable('inspection_standard_size_children');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('InspectionStandardSizeParents', [
            'foreignKey' => 'inspection_standard_size_parent_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('InspectionDataResultChildren', [
            'foreignKey' => 'inspection_standard_size_child_id'
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
            ->integer('size_number')
            ->requirePresence('size_number', 'create')
            ->notEmpty('size_number');

        $validator
            ->scalar('size_name')
            ->maxLength('size_name', 255)
            ->requirePresence('size_name', 'create')
            ->notEmpty('size_name');

        $validator
            ->scalar('input_type')
            ->maxLength('input_type', 255)
            ->requirePresence('input_type', 'create')
            ->notEmpty('input_type');

        $validator
            ->scalar('size')
            ->maxLength('size', 255)
            ->allowEmpty('size');

        $validator
            ->scalar('upper_limit')
            ->maxLength('upper_limit', 255)
            ->allowEmpty('upper_limit');

        $validator
            ->scalar('lower_limit')
            ->maxLength('lower_limit', 255)
            ->allowEmpty('lower_limit');

        $validator
            ->scalar('measuring_instrument')
            ->maxLength('measuring_instrument', 255)
            ->requirePresence('measuring_instrument', 'create')
            ->notEmpty('measuring_instrument');

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
        $rules->add($rules->existsIn(['inspection_standard_size_parent_id'], 'InspectionStandardSizeParents'));

        return $rules;
    }
}
