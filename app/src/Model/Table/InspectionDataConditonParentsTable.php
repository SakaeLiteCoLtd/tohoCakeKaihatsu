<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * InspectionDataConditonParents Model
 *
 * @property \App\Model\Table\InspectionDataConditonChildrenTable|\Cake\ORM\Association\HasMany $InspectionDataConditonChildren
 * @property |\Cake\ORM\Association\HasMany $InspectionDataResultParents
 *
 * @method \App\Model\Entity\InspectionDataConditonParent get($primaryKey, $options = [])
 * @method \App\Model\Entity\InspectionDataConditonParent newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\InspectionDataConditonParent[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\InspectionDataConditonParent|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InspectionDataConditonParent|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InspectionDataConditonParent patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\InspectionDataConditonParent[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\InspectionDataConditonParent findOrCreate($search, callable $callback = null, $options = [])
 */
class InspectionDataConditonParentsTable extends Table
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

        $this->setTable('inspection_data_conditon_parents');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('InspectionDataConditonChildren', [
            'foreignKey' => 'inspection_data_conditon_parent_id'
        ]);
        $this->hasMany('InspectionDataResultParents', [
            'foreignKey' => 'inspection_data_conditon_parent_id'
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
            ->dateTime('datetime')
            ->requirePresence('datetime', 'create')
            ->notEmpty('datetime');

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
