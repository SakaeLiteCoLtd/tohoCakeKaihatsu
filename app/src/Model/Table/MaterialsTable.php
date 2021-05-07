<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Materials Model
 *
 * @property |\Cake\ORM\Association\BelongsTo $Factories
 * @property |\Cake\ORM\Association\BelongsTo $Types
 * @property \App\Model\Table\PriceMaterialsTable|\Cake\ORM\Association\HasMany $PriceMaterials
 * @property |\Cake\ORM\Association\HasMany $ProductMaterialParents
 * @property |\Cake\ORM\Association\HasMany $不使用productMaterials
 *
 * @method \App\Model\Entity\Material get($primaryKey, $options = [])
 * @method \App\Model\Entity\Material newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Material[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Material|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Material|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Material patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Material[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Material findOrCreate($search, callable $callback = null, $options = [])
 */
class MaterialsTable extends Table
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

        $this->setTable('materials');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Factories', [
            'foreignKey' => 'factory_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('MaterialTypes', [
            'foreignKey' => 'type_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('PriceMaterials', [
            'foreignKey' => 'material_id'
        ]);
        $this->hasMany('ProductMaterialParents', [
            'foreignKey' => 'material_id'
        ]);
        $this->hasMany('不使用productMaterials', [
            'foreignKey' => 'material_id'
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
            ->scalar('material_code')
            ->maxLength('material_code', 255)
            ->requirePresence('material_code', 'create')
            ->notEmpty('material_code');

        $validator
            ->scalar('grade')
            ->maxLength('grade', 255)
            ->requirePresence('grade', 'create')
            ->notEmpty('grade');

        $validator
            ->scalar('color')
            ->maxLength('color', 255)
            ->requirePresence('color', 'create')
            ->notEmpty('color');

        $validator
            ->scalar('maker')
            ->maxLength('maker', 255)
            ->requirePresence('maker', 'create')
            ->notEmpty('maker');

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
        $rules->add($rules->existsIn(['type_id'], 'MaterialTypes'));

        return $rules;
    }
}
