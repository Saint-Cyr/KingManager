<?php

namespace TransactionBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class STransactionAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('totalAmount')
            ->add('createdAt', 'doctrine_orm_date_range', array('field_type'=>'sonata_type_date_range_picker',))
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('branch')
            ->add('sales')
            ->add('totalAmount')
            ->add('profit')
            ->add('createdAt')
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
        ->with('Products', array('class' => 'col-md-6'))
            ->add('sales', 'sonata_type_collection', array(
                'type_options' => array(
                    'delete' => false,
                    'delete_options' => array(
                        'type' => 'hidden',
                        'type_options' => array(
                            'mapped' => false,
                            'required' => false,
                        )
                    )
                )
            ), array(
                'edit' => 'inline',
                'inline' => 'table',
                'sortable' => 'position',
            ))
        ->end()
        ->with('Other', array('class' => 'col-md-4'))
              ->add('totalAmount', null, array('label' => 'Total Cash'))
              ->add('branch')
              ->add('createdAt', 'sonata_type_datetime_picker', array(
                      'dp_side_by_side'  => true,
                      'dp_use_current'   => false,
                      'dp_use_seconds'   => false,
              ))
        ->end()
        ->with('Transaction Type', array('class' => 'col-md-2'))
             ->add('oneTime', 'sonata_type_choice_field_mask', array('choices' => array('yes' => 'Yes', 'no' => 'no'),
                                                                      'map' => array('Yes' => array('totalAmount'),
                                                                                     'No' => array('no'))))
        ->end()
        ;
    }
    
    public function getBatchActions()
    {
        // retrieve the default batch actions (currently only delete)
        $actions = parent::getBatchActions();

        if (
          $this->hasRoute('edit') && $this->isGranted('EDIT') &&
          $this->hasRoute('delete') && $this->isGranted('DELETE')
            ) {
            $actions['report'] = array(
                'label' => 'Gen. Report',
                'translation_domain' => 'SonataAdminBundle',
                'ask_confirmation' => false
            );

        }

        return $actions;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('createdAt')
            ->add('totalAmount')
        ;
    }
    
    
    public function preUpdate($object) {
        
        foreach ($object->getSales() as $sale){
            $sale->setSTransaction($object);
        }
        
        parent::preUpdate($object);
    }
    
    public function prePersist($object) {
        
        foreach ($object->getSales() as $sale){
            $sale->setSTransaction($object);
        }
        
        parent::preUpdate($object);
    }
}
