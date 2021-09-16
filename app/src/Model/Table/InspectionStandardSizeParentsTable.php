<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * InspectionStandardSizeParents Model
 *
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 * @property \App\Model\Table\InspectionDataResultParentsTable|\Cake\ORM\Association\HasMany $InspectionDataResultParents
 * @property \App\Model\Table\InspectionStandardSizeChildrenTable|\Cake\ORM\Association\HasMany $InspectionStandardSizeChildren
 *
 * @method \App\Model\Entity\InspectionStandardSizeParent get($primaryKey, $options = [])
 * @method \App\Model\Entity\InspectionStandardSizeParent newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\InspectionStandardSizeParent[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\InspectionStandardSizeParent|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InspectionStandardSizeParent|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InspectionStandardSizeParent patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\InspectionStandardSizeParent[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\InspectionStandardSizeParent findOrCreate($search, callable $callback = null, $options = [])
 */
class InspectionStandardSizeParentsTable extends Table
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

        $this->setTable('inspection_standard_size_parents');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('InspectionDataResultParents', [
            'foreignKey' => 'inspection_standard_size_parent_id'
        ]);
        $this->hasMany('InspectionStandardSizeChildren', [
            'foreignKey' => 'inspection_standard_size_parent_id'
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
            ->scalar('image_file_name_dir')
            ->maxLength('image_file_name_dir', 255)
            ->requirePresence('image_file_name_dir', 'create')
            ->notEmpty('image_file_name_dir');

        $validator
            ->scalar('inspection_standard_size_code')
            ->maxLength('inspection_standard_size_code', 255)
            ->requirePresence('inspection_standard_size_code', 'create')
            ->notEmpty('inspection_standard_size_code');

        $validator
            ->integer('version')
            ->requirePresence('version', 'create')
            ->notEmpty('version');

        $validator
            ->integer('is_active')
            ->requirePresence('is_active', 'create')
            ->notEmpty('is_active');

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
        $rules->add($rules->existsIn(['product_id'], 'Products'));

        return $rules;
    }
}
