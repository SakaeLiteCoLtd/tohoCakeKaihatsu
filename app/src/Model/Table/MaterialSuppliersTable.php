<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * MaterialSuppliers Model
 *
 * @property \App\Model\Table\PriceMaterialsTable|\Cake\ORM\Association\HasMany $PriceMaterials
 *
 * @method \App\Model\Entity\MaterialSupplier get($primaryKey, $options = [])
 * @method \App\Model\Entity\MaterialSupplier newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MaterialSupplier[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MaterialSupplier|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MaterialSupplier|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MaterialSupplier patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MaterialSupplier[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MaterialSupplier findOrCreate($search, callable $callback = null, $options = [])
 */
class MaterialSuppliersTable extends Table
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

        $this->setTable('material_suppliers');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('PriceMaterials', [
            'foreignKey' => 'material_supplier_id'
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
            ->scalar('office')
            ->maxLength('office', 255)
            ->allowEmpty('office');

        $validator
            ->scalar('department')
            ->maxLength('department', 255)
            ->allowEmpty('department');

        $validator
            ->scalar('address')
            ->maxLength('address', 255)
            ->requirePresence('address', 'create')
            ->notEmpty('address');

        $validator
            ->scalar('tel')
            ->maxLength('tel', 255)
            ->requirePresence('tel', 'create')
            ->notEmpty('tel');

        $validator
            ->scalar('fax')
            ->maxLength('fax', 255)
            ->allowEmpty('fax');

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
}
