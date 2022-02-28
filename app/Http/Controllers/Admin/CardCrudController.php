<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CardRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CardCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CardCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Card::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/card');
        CRUD::setEntityNameStrings('card', 'cards');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        //CRUD::column('id');
        //CRUD::column('name');
        //CRUD::column('description');
        //CRUD::column('collection');
        //CRUD::column('created_at');
        //CRUD::column('updated_at');
        
        $this->crud->addColumn([
          'name' => 'name', // The db column name
          'label' => "Name", // Table column heading
          'type' => 'Text'
        ]);

        $this->crud->addColumn([
          'name' => 'description', // The db column name
          'label' => "Description", // Table column heading
          'type' => 'Text'
        ]);

        $this->crud->addColumn([
          'name' => 'collection', // The db column name
          'label' => "Collection", // Table column heading
          'type' => 'Text'
        ]);

        $this->crud->addColumn([
          'name' => 'image', // The db column name
          'label' => "Image", // Table column heading
          'type' => 'image'
        ]);

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(CardRequest::class);

        //CRUD::field('id');
        //CRUD::field('name');
        //CRUD::field('description');
        //CRUD::field('collection');
        //CRUD::field('created_at');
        //CRUD::field('updated_at');
       
        $this->crud->addField([
          'name' => 'name', // The db field name
          'label' => "Name", // Table field heading
          'type' => 'Text'
        ]);

        $this->crud->addField([
          'name' => 'description', // The db field name
          'label' => "Description", // Table field heading
          'type' => 'Text'
        ]);

        $this->crud->addField([
          'name' => 'collection', // The db field name
          'type'        => 'select_from_array',
          'options'     => ['one' => 'Collection Z', 'two' => 'Collection Y', 'three' => 'Collection X'],
          'allows_null' => false,
          'default'     => 'one',
        ]);

        $this->crud->addField([
          'name' => 'image',
          'label' => 'Image',
          'type' => 'upload',
          'upload' => true
          //'crop' => true, // set to true to allow cropping, false to disable
          //'aspect_ratio' => 1, // omit or set to 0 to allow any aspect ratio
          // 'disk'      => 's3_bucket', // in case you need to show images from a different disk
          // 'prefix'    => 'uploads/images/profile_pictures/' // in case your db value is only the file name (no path), you can use this to prepend your path to the image src (in HTML), before it's shown to the user;
        ]);
        
        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
