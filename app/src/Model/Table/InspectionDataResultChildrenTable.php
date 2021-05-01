<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * InspectionDataResultChildren Model
 *
 * @property \App\Model\Table\InspectionDataResultParentsTable|\Cake\ORM\Association\BelongsTo $InspectionDataResultParents
 * @property \App\Model\Table\InspectionStandardSizeChildrenTable|\Cake\ORM\Association\BelongsTo $InspectionStandardSizeChildren
 *
 * @method \App\Model\Entity\InspectionDataResultChild get($primaryKey, $options = [])
 * @method \App\Model\Entity\InspectionDataResultChild newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\InspectionDataResultChild[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\InspectionDataResultChild|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InspectionDataResultChild|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InspectionDataResultChild patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\InspectionDataResultChild[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\InspectionDataResultChild findOrCreate($search, callable $callback = null, $options = [])
 */
class InspectionDataResultChildrenTable extends Table
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

        $this->setTable('inspection_data_result_children');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('InspectionDataResultParents', [
            'foreignKey' => 'inspection_data_result_parent_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('InspectionStandardSizeChildren', [
            'foreignKey' => 'inspection_standard_size_child_id',
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
            ->numeric('result_size')
            ->requirePresence('result_size', 'create')
            ->notEmpty('result_size');

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
        $rules->add($rules->existsIn(['inspection_data_result_parent_id'], 'InspectionDataResultParents'));
        $rules->add($rules->existsIn(['inspection_standard_size_child_id'], 'InspectionStandardSizeChildren'));

        return $rules;
    }
}
