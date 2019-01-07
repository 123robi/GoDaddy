<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Teams Model
 *
 * @property \App\Model\Table\EventsTable|\Cake\ORM\Association\HasMany $Events
 * @property \App\Model\Table\FeesTable|\Cake\ORM\Association\HasMany $Fees
 * @property \App\Model\Table\PlacesTable|\Cake\ORM\Association\HasMany $Places
 * @property \App\Model\Table\TeamMembersTable|\Cake\ORM\Association\HasMany $TeamMembers
 * @property \App\Model\Table\UsersFeesTable|\Cake\ORM\Association\HasMany $UsersFees
 *
 * @method \App\Model\Entity\Team get($primaryKey, $options = [])
 * @method \App\Model\Entity\Team newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Team[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Team|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Team patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Team[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Team findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TeamsTable extends Table
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

        $this->setTable('teams');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Events', [
            'foreignKey' => 'team_id'
        ]);
        $this->hasMany('Fees', [
            'foreignKey' => 'team_id'
        ]);
        $this->hasMany('Places', [
            'foreignKey' => 'team_id'
        ]);
        $this->hasMany('TeamMembers', [
            'foreignKey' => 'team_id'
        ]);
        $this->hasMany('UsersFees', [
            'foreignKey' => 'team_id'
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
            ->scalar('team_name')
            ->maxLength('team_name', 255)
            ->requirePresence('team_name', 'create')
            ->notEmpty('team_name');

        $validator
            ->scalar('currency_code')
            ->maxLength('currency_code', 255)
            ->requirePresence('currency_code', 'create')
            ->notEmpty('currency_code');

        $validator
            ->scalar('currency_symbol')
            ->maxLength('currency_symbol', 255)
            ->requirePresence('currency_symbol', 'create')
            ->notEmpty('currency_symbol');

        $validator
            ->scalar('connection_number')
            ->maxLength('connection_number', 255)
            ->allowEmpty('connection_number')
            ->add('connection_number', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

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
        $rules->add($rules->isUnique(['connection_number']));

        return $rules;
    }
}
