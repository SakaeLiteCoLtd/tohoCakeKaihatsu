<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Staffs Model
 *
 * @property \App\Model\Table\FactoriesTable|\Cake\ORM\Association\BelongsTo $Factories
 * @property \App\Model\Table\DepartmentsTable|\Cake\ORM\Association\BelongsTo $Departments
 * @property \App\Model\Table\OccupationsTable|\Cake\ORM\Association\BelongsTo $Occupations
 * @property \App\Model\Table\PositionsTable|\Cake\ORM\Association\BelongsTo $Positions
 * @property \App\Model\Table\FactoriesTable|\Cake\ORM\Association\HasMany $Factories
 * @property |\Cake\ORM\Association\HasMany $InspectionDataResultParents
 * @property |\Cake\ORM\Association\HasMany $LoginStaffs
 * @property \App\Model\Table\StaffAbilitiesTable|\Cake\ORM\Association\HasMany $StaffAbilities
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\HasMany $Users
 * @property |\Cake\ORM\Association\HasMany $不使用productMaterialLotNumbers
 *
 * @method \App\Model\Entity\Staff get($primaryKey, $options = [])
 * @method \App\Model\Entity\Staff newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Staff[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Staff|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Staff|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Staff patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Staff[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Staff findOrCreate($search, callable $callback = null, $options = [])
 */
class StaffsTable extends Table
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

        $this->setTable('staffs');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Factories', [
            'foreignKey' => 'factory_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Departments', [
            'foreignKey' => 'department_id'
        ]);
        $this->belongsTo('Occupations', [
            'foreignKey' => 'occupation_id'
        ]);
        $this->belongsTo('Positions', [
            'foreignKey' => 'position_id'
        ]);
/*hasmanyは不要
        $this->hasMany('Factories', [
            'foreignKey' => 'staff_id'
        ]);
        $this->hasMany('InspectionDataResultParents', [
            'foreignKey' => 'staff_id'
        ]);
        $this->hasMany('LoginStaffs', [
            'foreignKey' => 'staff_id'
        ]);
        $this->hasMany('StaffAbilities', [
            'foreignKey' => 'staff_id'
        ]);
        $this->hasMany('Users', [
            'foreignKey' => 'staff_id'
        ]);
        $this->hasMany('不使用productMaterialLotNumbers', [
            'foreignKey' => 'staff_id'
        ]);
        */
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
            ->scalar('staff_code')
            ->maxLength('staff_code', 255)
            ->requirePresence('staff_code', 'create')
            ->notEmpty('staff_code');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->integer('sex')
            ->requirePresence('sex', 'create')
            ->notEmpty('sex');

        $validator
            ->scalar('mail')
            ->maxLength('mail', 255)
            ->allowEmpty('mail');

        $validator
            ->scalar('tel')
            ->maxLength('tel', 255)
            ->allowEmpty('tel');

        $validator
            ->scalar('address')
            ->maxLength('address', 255)
            ->allowEmpty('address');

        $validator
            ->date('birth')
            ->allowEmpty('birth');

        $validator
            ->date('date_start')
            ->allowEmpty('date_start');

        $validator
            ->date('date_finish')
            ->allowEmpty('date_finish');

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
        $rules->add($rules->existsIn(['department_id'], 'Departments'));
        $rules->add($rules->existsIn(['occupation_id'], 'Occupations'));
        $rules->add($rules->existsIn(['position_id'], 'Positions'));

        return $rules;
    }
}
