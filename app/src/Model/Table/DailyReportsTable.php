<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DailyReports Model
 *
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 * @property \App\Model\Table\InspectionDataResultParentsTable|\Cake\ORM\Association\HasMany $InspectionDataResultParents
 *
 * @method \App\Model\Entity\DailyReport get($primaryKey, $options = [])
 * @method \App\Model\Entity\DailyReport newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\DailyReport[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DailyReport|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DailyReport|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DailyReport patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DailyReport[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\DailyReport findOrCreate($search, callable $callback = null, $options = [])
 */
class DailyReportsTable extends Table
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

        $this->setTable('daily_reports');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('InspectionDataResultParents', [
            'foreignKey' => 'daily_report_id'
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
            ->integer('machine_num')
            ->requirePresence('machine_num', 'create')
            ->notEmpty('machine_num');

        $validator
            ->dateTime('start_datetime')
            ->requirePresence('start_datetime', 'create')
            ->notEmpty('start_datetime');

        $validator
            ->dateTime('finish_datetime')
            ->requirePresence('finish_datetime', 'create')
            ->notEmpty('finish_datetime');

        $validator
            ->integer('amount')
            ->requirePresence('amount', 'create')
            ->notEmpty('amount');

        $validator
            ->numeric('sum_weight')
            ->requirePresence('sum_weight', 'create')
            ->notEmpty('sum_weight');

        $validator
            ->numeric('total_loss_weight')
            ->requirePresence('total_loss_weight', 'create')
            ->notEmpty('total_loss_weight');

        $validator
            ->scalar('bik')
            ->allowEmpty('bik');

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
