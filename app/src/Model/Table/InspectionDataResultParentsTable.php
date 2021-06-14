<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * InspectionDataResultParents Model
 *
 * @property \App\Model\Table\InspectionDataConditonParentsTable|\Cake\ORM\Association\BelongsTo $InspectionDataConditonParents
 * @property \App\Model\Table\InspectionStandardSizeParentsTable|\Cake\ORM\Association\BelongsTo $InspectionStandardSizeParents
 * @property \App\Model\Table\ProductConditonParentsTable|\Cake\ORM\Association\BelongsTo $ProductConditonParents
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 * @property \App\Model\Table\StaffsTable|\Cake\ORM\Association\BelongsTo $Staffs
 * @property \App\Model\Table\InspectionDataResultChildrenTable|\Cake\ORM\Association\HasMany $InspectionDataResultChildren
 *
 * @method \App\Model\Entity\InspectionDataResultParent get($primaryKey, $options = [])
 * @method \App\Model\Entity\InspectionDataResultParent newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\InspectionDataResultParent[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\InspectionDataResultParent|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InspectionDataResultParent|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InspectionDataResultParent patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\InspectionDataResultParent[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\InspectionDataResultParent findOrCreate($search, callable $callback = null, $options = [])
 */
class InspectionDataResultParentsTable extends Table
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

        $this->setTable('inspection_data_result_parents');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('InspectionDataConditonParents', [
            'foreignKey' => 'inspection_data_conditon_parent_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('InspectionStandardSizeParents', [
            'foreignKey' => 'inspection_standard_size_parent_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ProductConditonParents', [
            'foreignKey' => 'product_conditon_parent_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Staffs', [
            'foreignKey' => 'staff_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('InspectionDataResultChildren', [
            'foreignKey' => 'inspection_data_result_parent_id'
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
            ->scalar('lot_number')
            ->maxLength('lot_number', 255)
            ->requirePresence('lot_number', 'create')
            ->notEmpty('lot_number');

        $validator
            ->dateTime('datetime')
            ->requirePresence('datetime', 'create')
            ->notEmpty('datetime');

        $validator
            ->integer('appearance')
            ->requirePresence('appearance', 'create')
            ->notEmpty('appearance');

        $validator
            ->numeric('result_weight')
            ->requirePresence('result_weight', 'create')
            ->notEmpty('result_weight');

        $validator
            ->integer('judge')
            ->requirePresence('judge', 'create')
            ->notEmpty('judge');

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
        $rules->add($rules->existsIn(['inspection_standard_size_parent_id'], 'InspectionStandardSizeParents'));
  //      $rules->add($rules->existsIn(['product_conditon_parent_id'], 'ProductConditonParents'));
        $rules->add($rules->existsIn(['product_id'], 'Products'));
        $rules->add($rules->existsIn(['staff_id'], 'Staffs'));

        return $rules;
    }
}
