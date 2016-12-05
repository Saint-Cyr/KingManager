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
        ->with('Products', array('class' => 'col-md-4'))
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
        ->end()
        ;
        
        /*$formMapper
            ->add('totalAmount')
            ->add('sales')
            ->add('branch')
            ->add('createdAt')
        ;*/
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
