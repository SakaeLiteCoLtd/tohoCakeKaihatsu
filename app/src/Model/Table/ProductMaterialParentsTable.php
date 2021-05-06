<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductMaterialParents Model
 *
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 * @property |\Cake\ORM\Association\BelongsTo $Materials
 * @property \App\Model\Table\InspectionDataResultParentsTable|\Cake\ORM\Association\HasMany $InspectionDataResultParents
 * @property \App\Model\Table\ProductMaterialMachinesTable|\Cake\ORM\Association\HasMany $ProductMaterialMachines
 *
 * @method \App\Model\Entity\ProductMaterialParent get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProductMaterialParent newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProductMaterialParent[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductMaterialParent|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductMaterialParent|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductMaterialParent patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProductMaterialParent[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductMaterialParent findOrCreate($search, callable $callback = null, $options = [])
 */
class ProductMaterialParentsTable extends Table
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

        $this->setTable('product_material_parents');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Materials', [
            'foreignKey' => 'material_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('InspectionDataResultParents', [
            'foreignKey' => 'product_material_parent_id'
        ]);
        $this->hasMany('ProductMaterialMachines', [
            'foreignKey' => 'product_material_parent_id'
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
        $rules->add($rules->existsIn(['material_id'], 'Materials'));

        return $rules;
    }
}
