<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController as BaseCrudController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

/**
 * App specific CrudController
 */
abstract class CrudController extends BaseCrudController
{
    protected $locale;
    protected $parentEntityName;
    protected $parentEntityId;

    protected $modelClass = null;
    protected $entityName = null;
    protected $crudRouteUrl = null;

    protected $addTimeStampColumns = true;
    protected $addTimeStampFields = true;
    protected $defaultSortByColumn = 'created_at';
    protected $defaultSortOrder = 'desc';


    /**
     * @throws \Exception
     */
    public function setup()
    {
        $this->request               = $this->crud->request = $this->request ?? request();
        $this->locale                = $this->request->get('locale', \App::getLocale());
        $this->parentEntityName      = $this->request->get('parent_name');
        $this->parentEntityId        = $this->request->get('parent_id');
        $this->data[ 'fieldAccess' ] = [];

        $this->callHook('beforeCrudSetup');

        if ( $this->modelClass ) {
            $this->crud->setModel($this->modelClass);
        } else {
            $modelClass = 'App\\Models\\' . preg_replace('/CrudController$/', '', class_basename(get_class($this)));
            if ( is_a($modelClass, Model::class, true) ) {
                $this->modelClass = $modelClass;
                $this->crud->setModel($this->modelClass);
            } else {
                throw new \AssertionError("modelClass property cannot be determined from controller class name. Must be set in derived controller class.");
            }
        }

        $this->entityName = $this->entityName ?? snake_case(class_basename($this->modelClass), '-');

        if ( empty($this->crudRouteUrl) ) {
            $routeName = sprintf('crud.%s.index', $this->entityName);
            if ( \Route::has($routeName) ) {
                $this->crudRouteUrl = route($routeName);
            } else {
                throw new \AssertionError("crudRouteUrl property cannot be automatically resolved from entityName or controller class name. crudRouteUrl or a valid entityName must be set in derived controller class.");
            }
        }

        $this->crud->setRoute($this->crudRouteUrl);

        $nameI18nKey = sprintf("entity.%s", $this->entityName);

        $this->crud->setEntityNameStrings(strtolower(trans_choice($nameI18nKey, 1)), strtolower(trans_choice($nameI18nKey, 2)));

        $this->callHook('afterCrudSetup');

        $this->callHook('setupCrudFields');

        $this->callHook('setupDataTable');

        $this->callHook('finishCrudSetup');

        $this->callHook('setupDataTableFilter');

        $this->defaultSortByColumn = in_array($this->defaultSortByColumn, [ 'created_at', 'updated_at' ]) ? ( $this->crud->model->usesTimestamps() ? $this->defaultSortByColumn : false ) : $this->defaultSortByColumn;

        if ( $this->defaultSortByColumn && empty($this->crud->orders) && empty($this->crud->unionOrders) && empty($this->request->get('order')) ) {
            // add default sort order only if no explicit sort order is active & model uses timestamps
            $defaultSortByColumn = $this->crud->model->getTable() . "." . $this->defaultSortByColumn;
            $this->crud->orderBy($defaultSortByColumn, in_array($this->defaultSortOrder, [ 'desc', 'asc' ]) ? $this->defaultSortOrder : 'desc');
        }

        if ( $this->crud->model->usesTimestamps() && ( $this->addTimeStampColumns || $this->addTimeStampFields ) ) {
            foreach ( [ 'created_at', 'updated_at' ] as $timestampColumn ) {
                if ( $this->addTimeStampColumns && !isset($this->crud->columns[ $timestampColumn ]) ) {
                    $this->crud->addColumn([
                        'label'       => trans('validation.attributes.' . $timestampColumn),
                        'name'        => $timestampColumn,
                        'type'        => 'datetime',
                        'searchLogic' => false
                    ]);
                }

                if ( $this->addTimeStampFields && !isset($this->crud->update_fields[ $timestampColumn ]) ) {
                    $fieldMeta = [
                        'label' => trans('validation.attributes.' . $timestampColumn),
                        'name'  => $timestampColumn,
                        'type'  => 'datetime',
                        'attributes' => [
                            'disabled' => 'disabled'
                        ]
                    ];

                    if ( $this->crud->tabsEnabled() ) {
                        $fieldMeta[ 'tab' ] = $this->crud->getLastTab();
                    }

                    $this->crud->addField($fieldMeta, 'update');
                }
            }
        }
    }

    /**
     * @param $hookNames
     * @param mixed ...$hookParams
     *
     * @return mixed|null
     */
    private function callHook( $hookNames, ...$hookParams )
    {
        foreach ( (array) $hookNames as $hookName ) {
            if ( method_exists($this, $hookName) ) {
                $returnValue = call_user_func_array([ $this, $hookName ], $hookParams);
                if ( $returnValue ) {
                    return $returnValue;
                }
            }
        }

        return null;
    }

    /**
     * Display all rows in the database for this entity.
     *
     * @return Response
     * @throws \Backpack\CRUD\Exception\AccessDeniedException
     */
    public function index()
    {
        $this->callHook('beforeIndex');
        $this->crud->hasAccessOrFail('list');

        $this->data[ 'crud' ]  = $this->crud;
        $this->data[ 'title' ] = ucfirst($this->crud->entity_name_plural);

        // get all entries if AJAX is not enabled
        if ( !$this->data[ 'crud' ]->ajaxTable() ) {
            $this->data[ 'entries' ] = $this->data[ 'crud' ]->getEntries();
        }

        $this->callHook('afterIndex');

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getListView(), $this->data);
    }

    public function search()
    {
        $this->callHook('beforeSearch');
        $this->crud->hasAccessOrFail('list');

        return parent::search();
    }

    /**
     * Show the form for creating inserting a new row.
     *
     * @return Response
     * @throws \Backpack\CRUD\Exception\AccessDeniedException
     */
    public function create()
    {
        $this->callHook('beforeCreate');
        $this->crud->hasAccessOrFail('create');

        $this->data[ 'crud' ]       = $this->crud;
        $this->data[ 'saveAction' ] = $this->getSaveAction();

        // prepare the fields you need to show
        if ( $hookReturn = $this->callHook([ 'beforeCreate' ]) ) {
            return $hookReturn;
        }

        // apply field access checking here
        $this->data[ 'fields' ] = $this->crud->getCreateFields();
        $this->data[ 'title' ]  = trans('backpack::crud.add') . ' ' . $this->crud->entity_name;

        $this->callHook('afterCreate');
        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getCreateView(), $this->data);
    }

    /**
     * Store a newly created resource in the database.
     *
     * @param Request $request - type injection used for validation using Requests
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Backpack\CRUD\Exception\AccessDeniedException
     */
    public function storeCrud( Request $request = null )
    {
        $this->crud->hasAccessOrFail('create');

        // fallback to global request instance
        if ( is_null($request) ) {
            $request = $this->request;
        }

        try {
            return $this->wrapInTransaction(function ( $request ) {
                if ( $hookReturn = $this->callHook([ 'beforeStore', 'beforeSave' ], $request) ) {
                    return $hookReturn;
                }

                // replace empty values with NULL, so that it will work with MySQL strict mode on
                foreach ( $request->input() as $key => $value ) {
                    if ( empty($value) && $value !== '0' ) {
                        $request->request->set($key, null);
                    }
                }

                // insert item in the db
                $entry                 = $this->crud->create($request->except([ 'save_action', '_token', '_method' ]));
                $this->data[ 'entry' ] = $this->crud->entry = $entry;

                if ( $hookReturn = $this->callHook([ 'afterStore', 'afterSave' ], $request, $entry) ) {
                    return $hookReturn;
                }

                // show a success message
                \Alert::success(trans('backpack::crud.insert_success'))->flash();

                // save the redirect choice for next time
                $this->setSaveAction();

                return $this->performSaveAction($entry->getKey());
            }, $request);
        } catch ( \Exception $ex ) {
            $message = $ex->getMessage();
            if ( property_exists($ex, 'validator') ) {
                if ( is_string($ex->validator) ) {
                    $message = $ex->validator;
                } elseif ( is_a($ex->validator, Validator::class) && $ex->validator->errors()->isNotEmpty() ) {
                    $message = implode(',', $ex->validator->errors()->all());
                }
            }
            \Log::error("[CrudController::updateCrud] " . $message, [ 'exception' => $ex ]);
            \Alert::error($message)->flash();
        }

        return redirect()->back()->withInput();
    }

    /**
     * @param $callable
     * @param mixed ...$args
     *
     * @return mixed
     * @throws \Exception
     */
    protected function wrapInTransaction( $callable, ...$args )
    {
        if ( !is_callable($callable) ) {
            throw new \InvalidArgumentException("Parameter \$callable be a callable.");
        }

        /**
         * @var $connection \Illuminate\Database\Connection
         */
        $connection = $this->crud->model->getConnection();

        $connection->beginTransaction();
        try {
            $returnValue = call_user_func_array($callable, $args);
            $connection->commit();

            return $returnValue;
        } catch ( \Exception $ex ) {
            $connection->rollBack();
            throw $ex;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     * @throws \Backpack\CRUD\Exception\AccessDeniedException
     */
    public function edit($id)
    {
        $this->crud->hasAccessOrFail('update');

        // get the info for that entry
        $this->data['entry'] = $this->crud->entry = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['saveAction'] = $this->getSaveAction();

        if ($hookReturn = $this->callHook('beforeEdit', $this->crud->entry)) {
            return $hookReturn;
        }

        // TODO: apply field access checking here
        $this->data['fields'] = $this->crud->getUpdateFields($id);
        $this->data['title'] = trans('backpack::crud.edit') . ' ' . $this->crud->entity_name;

        $this->data['id'] = $id;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getEditView(), $this->data);
    }

    /**
     * Update the specified resource in the database.
     *
     * @param Request $request - type injection used for validation using Requests
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Backpack\CRUD\Exception\AccessDeniedException
     */
    public function updateCrud( Request $request = null )
    {
        $this->crud->hasAccessOrFail('update');

        // fallback to global request instance
        if ( is_null($request) ) {
            $request = $this->request;
        }

        try {
            return $this->wrapInTransaction(function ( $request ) {
                if ( $hookReturn = $this->callHook([ 'beforeUpdate', 'beforeSave' ], $request) ) {
                    return $hookReturn;
                }

                // replace empty values with NULL, so that it will work with MySQL strict mode on
                foreach ( $request->input() as $key => $value ) {
                    if ( empty($value) && $value !== '0' ) {
                        $request->request->set($key, null);
                    }
                }

                // insert item in the db
                $entry                 = $this->crud->update($request->get($this->crud->model->getKeyName()), $request->except('save_action', '_token', '_method'));
                $this->data[ 'entry' ] = $this->crud->entry = $entry;

                if ( $hookReturn = $this->callHook([ 'afterUpdate', 'afterSave' ], $request, $entry) ) {
                    return $hookReturn;
                }

                // show a success message
                \Alert::success(trans('backpack::crud.update_success'))->flash();

                // save the redirect choice for next time
                $this->setSaveAction();

                return $this->performSaveAction($entry->getKey());
            }, $request);
        } catch ( \Exception $ex ) {
            $message = $ex->getMessage();
            if ( property_exists($ex, 'validator') ) {
                if ( is_string($ex->validator) ) {
                    $message = $ex->validator;
                } elseif ( is_a($ex->validator, Validator::class) && $ex->validator->errors()->isNotEmpty() ) {
                    $message = implode(',', $ex->validator->errors()->all());
                }
            }
            \Log::error("[CrudController::updateCrud] " . $message, [ 'exception' => $ex ]);
            \Alert::error($message)->flash();

            return redirect()->back()->withInput();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return string
     * @throws \Backpack\CRUD\Exception\AccessDeniedException
     */
    public function destroy( $id )
    {
        $this->crud->hasAccessOrFail('delete');

        try {
            return $this->wrapInTransaction(function ( $id ) {
                if ( $hookReturn = $this->callHook('beforeDelete', $id) ) {
                    return (string) $hookReturn;
                }

                $deleteReturn = $this->crud->delete($id);

                if ( $hookReturn = $this->callHook('afterDelete', $id) ) {
                    return (string) $hookReturn;
                }

                return $deleteReturn;
            }, $id);
        } catch ( \Exception $ex ) {
            \Log::error("[CrudController::destroy] " . $ex->getMessage(), [ 'exception' => $ex ]);

            return abort(412, $ex->getMessage());
        }
    }

    /**
     * @param $fieldName
     * @param $newSettingOrCallback
     * @param string $fieldType
     */
    public function modifyFieldSetting( $fieldName, $newSettingOrCallback, $fieldType = 'update' )
    {
        $fieldsAttribute = "{$fieldType}_fields";
        $fieldMeta       =& $this->crud->$fieldsAttribute[ $fieldName ];
        if ( $fieldMeta ) {
            if ( is_array($newSettingOrCallback) ) {
                foreach ( $newSettingOrCallback as $key => $value ) {
                    if ( isset($fieldMeta[ $key ]) && is_array($fieldMeta[ $key ]) && is_array($value) && !isset($newSettingOrCallback[ 'force' ]) ) {
                        $fieldMeta[ $key ] = array_replace_recursive($fieldMeta[ $key ], $value);
                    } else {
                        $fieldMeta[ $key ] = $value;
                    }
                }
            } else if ( is_callable($newSettingOrCallback) ) {
                call_user_func($newSettingOrCallback, $fieldMeta);
            }

        }
    }

    public function hasField( $fieldName )
    {
        $allFields = $this->getAllFields();
        if ( isset($allFields[ 'create' ][ $fieldName ]) || isset($allFields[ 'update' ][ $fieldName ]) ) {
            return true;
        }

        return false;
    }

    public function getAllFields( $types = 'both' )
    {
        $types  = $types == 'both' ? [ 'create', 'update' ] : ( is_array($types) ? $types : [ $types ] );
        $fields = [];
        foreach ( $types as $fieldType ) {
            $fieldsAttribute      = "{$fieldType}_fields";
            $fields[ $fieldType ] = $this->crud->$fieldsAttribute;
        }

        return $fields;
    }

    public function removeAllFields( $types = 'both' )
    {
        $types = $types == 'both' ? [ 'create', 'update' ] : ( is_array($types) ? $types : [ $types ] );
        foreach ( $types as $fieldType ) {
            $fieldsAttribute              = "{$fieldType}_fields";
            $this->crud->$fieldsAttribute = [];
        }
    }

    protected function redirectToParent( $parentId = null )
    {
        return redirect()->route('crud.' . $this->parentEntityName . '.edit', [ $this->parentEntityName => $parentId ?? $this->parentEntityId ]);
    }

    protected function addDetailListField( $crudControllerClass, $parent, $callback, ...$callbackArgs )
    {
        if ( !is_a($crudControllerClass, CrudController::class, true) ) {
            return false;
        }

        $crudController = new $crudControllerClass();
        $crudController->setup();

        $detailListCrud = $crudController->crud;

        call_user_func_array($callback, array_merge([ $detailListCrud, $parent ], $callbackArgs));

        $detailListData = [
            'crud'          => $detailListCrud,
            'parentEntry'   => $parent,
            'parentContext' => [
                'parent_name' => $this->entityName,
                'parent_id'   => $parent->getKey()
            ],
            'title'         => ucfirst($detailListCrud->entity_name_plural),
            'entries'       => $detailListCrud->getEntries(),
        ];

        return $this->crud->addField([
            'label' => $detailListData[ 'title' ],
            'name'  => 'bundles',
            'type'  => 'detail_list',
            'data'  => $detailListData,
        ]);
    }

    protected function applyFieldAccess()
    {
        $className = $this->fieldAccessCurrentClassName();
        if ( file_exists(app_path('FieldAccess/' . $className . '.php')) ) {
            app('App\FieldAccess\\' . $className)->apply($this);
        }
    }

    protected function fieldAccessCurrentClassName()
    {
        $currentRouteName = str_replace('.', '_', \Request::route()->getName());
        $className        = studly_case('field_access_' . $currentRouteName);

        return $className;
    }
}
