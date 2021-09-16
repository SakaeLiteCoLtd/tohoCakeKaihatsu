<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Products Model
 *
 * @property \App\Model\Table\FactoriesTable|\Cake\ORM\Association\BelongsTo $Factories
 * @property \App\Model\Table\CustomersTable|\Cake\ORM\Association\BelongsTo $Customers
 * @property \App\Model\Table\InspectionDataResultParentsTable|\Cake\ORM\Association\HasMany $InspectionDataResultParents
 * @property \App\Model\Table\InspectionStandardSizeParentsTable|\Cake\ORM\Association\HasMany $InspectionStandardSizeParents
 * @property \App\Model\Table\PriceProductsTable|\Cake\ORM\Association\HasMany $PriceProducts
 * @property \App\Model\Table\ProductConditionParentsTable|\Cake\ORM\Association\HasMany $ProductConditionParents
 *
 * @method \App\Model\Entity\Product get($primaryKey, $options = [])
 * @method \App\Model\Entity\Product newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Product[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Product|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Product|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Product patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Product[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Product findOrCreate($search, callable $callback = null, $options = [])
 */
class ProductsTable extends Table
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

        $this->setTable('products');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Factories', [
            'foreignKey' => 'factory_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Customers', [
            'foreignKey' => 'customer_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('InspectionDataResultParents', [
            'foreignKey' => 'product_id'
        ]);
        $this->hasMany('InspectionStandardSizeParents', [
            'foreignKey' => 'product_id'
        ]);
        $this->hasMany('PriceProducts', [
            'foreignKey' => 'product_id'
        ]);
        $this->hasMany('ProductConditionParents', [
            'foreignKey' => 'product_id'
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
            ->scalar('product_code')
            ->maxLength('product_code', 255)
            ->requirePresence('product_code', 'create')
            ->notEmpty('product_code');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('tanni')
            ->maxLength('tanni', 255)
            ->allowEmpty('tanni');

        $validator
            ->numeric('length')
            ->requirePresence('length', 'create')
            ->notEmpty('length');

        $validator
            ->numeric('length_cut')
            ->requirePresence('length_cut', 'create')
            ->notEmpty('length_cut');

        $validator
            ->numeric('length_size')
            ->allowEmpty('length_size');

        $validator
            ->numeric('length_upper_limit')
            ->allowEmpty('length_upper_limit');

        $validator
            ->numeric('length_lower_limit')
            ->allowEmpty('length_lower_limit');

        $validator
            ->integer('status_kensahyou')
            ->requirePresence('status_kensahyou', 'create')
            ->notEmpty('status_kensahyou');

        $validator
            ->integer('ig_bank_modes')
            ->allowEmpty('ig_bank_modes');

        $validator
            ->numeric('weight')
            ->allowEmpty('weight');

        $validator
            ->scalar('sakuin')
            ->maxLength('sakuin', 255)
            ->allowEmpty('sakuin');

        $validator
            ->scalar('bik')
            ->allowEmpty('bik');

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
        $rules->add($rules->existsIn(['factory_id'], 'Factories'));
        $rules->add($rules->existsIn(['customer_id'], 'Customers'));

        return $rules;
    }
}
