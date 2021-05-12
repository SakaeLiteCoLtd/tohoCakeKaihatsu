<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductConditionParents Model
 *
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 * @property \App\Model\Table\ProductConditonChildrenTable|\Cake\ORM\Association\HasMany $ProductConditonChildren
 * @property \App\Model\Table\ShotWorksTable|\Cake\ORM\Association\HasMany $ShotWorks
 * @property \App\Model\Table\ShotdataBasesTable|\Cake\ORM\Association\HasMany $ShotdataBases
 *
 * @method \App\Model\Entity\ProductConditionParent get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProductConditionParent newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProductConditionParent[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductConditionParent|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductConditionParent|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductConditionParent patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProductConditionParent[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductConditionParent findOrCreate($search, callable $callback = null, $options = [])
 */
class ProductConditionParentsTable extends Table
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

        $this->setTable('product_condition_parents');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ProductConditonChildren', [
            'foreignKey' => 'product_condition_parent_id'
        ]);
        $this->hasMany('ShotWorks', [
            'foreignKey' => 'product_condition_parent_id'
        ]);
        $this->hasMany('ShotdataBases', [
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
            ->integer('version')
            ->requirePresence('version', 'create')
            ->notEmpty('version');

        $validator
            ->dateTime('start_datetime')
            ->requirePresence('start_datetime', 'create')
            ->notEmpty('start_datetime');

        $validator
            ->dateTime('finish_datetime')
            ->allowEmpty('finish_datetime');

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
