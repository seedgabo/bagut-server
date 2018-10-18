<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApiController extends Controller
{
    public $id = "id";
    public $model;
    public $indexRules = [];
    public $showRules = [];
    public $storeRules = [];
    public $updateRules = [];
    public $deleteRules = [];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(!$this->canList($request), 401, "Unauthorized");
        $this->validate($request, $this->indexRules);
        $class = $this->model;
        if ($request->has('select')) {
            $query = $class::select(DB::raw($request->input('select')));
        } else {
            $query = $class::select("*");
        }
        $query = $this->PreScope($request, $query);

        if ($request->has('where')) {
            foreach ($request->input('where') as $key => $value) {
                $query = $query->where($key, $value);
            }
        }
        if ($request->has('whereNot')) {
            foreach ($request->input('whereNot') as $key => $value) {
                $query = $query->where($key, "<>", $value);
            }
        }
        if ($request->has('orWhere')) {
            foreach ($request->input('orWhere') as $key => $value) {
                $query = $query->orWhere($key, $value);
            }
        }
        if ($request->has('orWhereNot')) {
            foreach ($request->input('whereNot') as $key => $value) {
                $query = $query->orWhere($key, "<>", $value);
            }
        }
        if ($request->has('whereGt')) {
            foreach ($request->input('whereGt') as $key => $value) {
                $query = $query->where($key, ">", $value);
            }
        }
        if ($request->has('orWhereGt')) {
            foreach ($request->input('orWhereGt') as $key => $value) {
                $query = $query->orWhere($key, ">", $value);
            }
        }
        if ($request->has('whereGte')) {
            foreach ($request->input('whereGte') as $key => $value) {
                $query = $query->where($key, ">=", $value);
            }
        }
        if ($request->has('orWhereGte')) {
            foreach ($request->input('orWhereGte') as $key => $value) {
                $query = $query->orWhere($key, ">=", $value);
            }
        }
        if ($request->has('whereLt')) {
            foreach ($request->input('whereLt') as $key => $value) {
                $query = $query->where($key, "<", $value);
            }
        }
        if ($request->has('orWhereLt')) {
            foreach ($request->input('orWhereLt') as $key => $value) {
                $query = $query->orWhere($key, "<", $value);
            }
        }
        if ($request->has('whereLte')) {
            foreach ($request->input('whereLte') as $key => $value) {
                $query = $query->where($key, "<=", $value);
            }
        }
        if ($request->has('orWhereLte')) {
            foreach ($request->input('orWhereLte') as $key => $value) {
                $query = $query->orWhere($key, "<=", $value);
            }
        }

        if ($request->has('whereNull')) {
            foreach ($request->input('whereNull') as $key => $value) {
                $query = $query->whereNull($value);
            }
        }
        if ($request->has('orWhereNull')) {
            foreach ($request->input('orWhereNull') as $key => $value) {
                $query = $query->orWhereNull($value);
            }
        }
        if ($request->has('whereNotNull')) {
            foreach ($request->input('whereNotNull') as $key => $value) {
                $query = $query->whereNotNull($value);
            }
        }
        if ($request->has('orWhereNotNull')) {
            foreach ($request->input('orWhereNotNull') as $key => $value) {
                $query = $query->orWhereNotNull($value);
            }
        }
        if ($request->has('whereLike')) {
            foreach ($request->input('whereLike') as $key => $value) {
                $query = $query->where($key, "LIKE", "%". $value ."%");
            }
        }
        if ($request->has('orWhereLike')) {
            foreach ($request->input('orWhereLike') as $key => $value) {
                $query = $query->orWhere($key, "LIKE", "%". $value ."%");
            }
        }
        if ($request->has('whereDate')) {
            foreach ($request->input('whereDate') as $key => $value) {
                $query = $query->where($key, Carbon::parse($value));
            }
        }
        if ($request->has('orWhereDate')) {
            foreach ($request->input('orWhereDate') as $key => $value) {
                $query = $query->orWhere($key, Carbon::parse($value));
            }
        }
        if ($request->has('whereDategt')) {
            foreach ($request->input('whereDategt') as $key => $value) {
                $query = $query->where($key, ">", Carbon::parse($value));
            }
        }
        if ($request->has('orWhereDategt')) {
            foreach ($request->input('orWhereDategt') as $key => $value) {
                $query = $query->orWhere($key, ">", Carbon::parse($value));
            }
        }
        if ($request->has('whereDategte')) {
            foreach ($request->input('whereDategte') as $key => $value) {
                $query = $query->where($key, ">=", Carbon::parse($value));
            }
        }
        if ($request->has('orWhereDategte')) {
            foreach ($request->input('orWhereDategte') as $key => $value) {
                $query = $query->orWhere($key, ">=", Carbon::parse($value));
            }
        }
        if ($request->has('whereDatelw')) {
            foreach ($request->input('whereDatelw') as $key => $value) {
                $query = $query->where($key, "<", Carbon::parse($value));
            }
        }
        if ($request->has('orWhereDatelw')) {
            foreach ($request->input('orWhereDatelw') as $key => $value) {
                $query = $query->orWhere($key, "<", Carbon::parse($value));
            }
        }
        if ($request->has('whereDatelwe')) {
            foreach ($request->input('whereDatelwe') as $key => $value) {
                $query = $query->where($key, "<=", Carbon::parse($value));
            }
        }
        if ($request->has('orWhereDatelwe')) {
            foreach ($request->input('orWhereDatelwe') as $key => $value) {
                $query = $query->orWhere($key, "<", Carbon::parse($value));
            }
        }
        if ($request->has('whereDateBetween')) {
            foreach ($request->input('whereDateBetween') as $key => $value) {
                $value = explode(",", $value);
                $query = $query->WhereBetween($key, [Carbon::parse($value[0]), Carbon::parse($value[1])]);
            }
        }
        if ($request->has('orWhereDateBetween')) {
            foreach ($request->input('orWhereDateBetween') as $key => $value) {
                $value = explode(",", $value);
                $query = $query->orWhereBetween($key, [Carbon::parse($value[0]), Carbon::parse($value[1])]);
            }
        }
        if ($request->has('whereBetween')) {
            foreach ($request->input('whereBetween') as $key => $value) {
                $value = explode(",", $value);
                $query = $query->WhereBetween($key, [$value[0], $value[1]]);
            }
        }
        if ($request->has('orWhereBetween')) {
            foreach ($request->input('orWhereBetween') as $key => $value) {
                $value = explode(",", $value);
                $query = $query->orWhereBetween($key, [$value[0], $value[1]]);
            }
        }
        if ($request->has('with')) {
            foreach ($request->input('with') as $value) {
                $query = $query->with($value);
            }
        }
        if ($request->has('withCount')) {
            foreach ($request->input('withCount') as $value) {
                $query = $query->withCount($value . " as ". $value . "_count");
            }
        }
        if ($request->has('has')) {
            foreach ($request->input('has') as $value) {
                $query = $query->has($value);
            }
        }
        if ($request->has('doesntHave')) {
            foreach ($request->input('doesntHave') as $value) {
                $query = $query->doesntHave($value);
            }
        }
        if ($request->has('whereIn')) {
            foreach ($request->input('whereIn') as $key => $value) {
                $query = $query->whereIn($key, explode(",", $value));
            }
        }
        if ($request->has('orWhereIn')) {
            foreach ($request->input('orWhereIn') as $key => $value) {
                $query = $query->orwhereIn($key, explode(",", $value));
            }
        }
        if ($request->has('limit')) {
            $query = $query->take($request->input('limit', 200));
        }
        else if(!$request->has('paginate') && !$request->has('count')){
            $query = $query->take($request->input('limit', 5000));
        }
        if ($request->has('scope')) {
            foreach ($request->input('scope') as $key => $value) {
                $query = $query->{$key}($value);
            }
        }
        if ($request->has('order')) {
            foreach ($request->input('order') as $key => $value) {
                $query = $query->orderBy($key, $value);
            }
        }
        if ($request->has('group')) {
            foreach ($request->input('group') as $key => $value) {
                $query = $query->groupBy($value);
            }
        }
        if ($request->has('paginate')) {
            $results = $query->paginate($request->input('paginate'));
        } elseif ($request->has('count')) {
            return $results = $query->count();
        } else {
            $results = $query->get();
        }

        if ($request->has('afterEach')) {
            foreach ($request->input('afterEach') as $key => $value) {
                if (isset($value)) {
                    $results->each->{$key}($value);
                } else {
                    $results = $results->each->{$key}();
                }
            }
        }

        if ($request->has('attr')) {
            foreach ($request->input('attr') as $key => $value) {
                foreach ($results as $model) {
                    $model->{$key} = $model->{$value};
                }
            }
        }
        
        if ($request->has('append')) {
            foreach ($request->input('append') as $key => $value) {
                foreach ($results as $model) {
                    $model->append($value);
                }
            }
        }


        if ($request->has('afterQuery')) {
            foreach ($request->input('afterQuery') as $key => $value) {
                if (isset($value)) {
                    $results = $results->{$key}($value);
                } else {
                    $results = $results->{$key};
                }
            }
        }

        return $results;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(!$this->canStore($request), 401, "Unauthorized");

        $this->validate($request, $this->storeRules);
        $class = $this->model;
        return $class::create($request->except('with'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        abort_if(!$this->canView($request, $id), 401, "Unauthorized");
        $class = $this->model;
        $this->validate($request, $this->showRules);
        $entity = $class::find($id);
        if ($request->has('with')) {
            foreach ($request->input('with') as $value) {
                $entity->load($value);
            }
        }

        if ($request->has('attr')) {
            foreach ($request->input('attr') as $key => $value) {
                 $entity->{$key} = $entity->{$value};
            }
        }
        if ($request->has('append')) {
            foreach ($request->input('append') as $key => $value) {
                 $entity->append($value);
            }
        }
        return $entity;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        abort_if(!$this->canUpdate($request, $id), 401, "Unauthorized");

        $this->validate($request, $this->updateRules);
        $class = $this->model;
        $entity = $class::findorFail($id);
        $entity->fill($request->except('with'))->save();
        if ($request->has('with')) {
            foreach ($request->input('with') as $value) {
                $entity->{$value};
            }
        }
        return  $entity;
    }


    public function updateMany(Request $request)
    {
        abort_if(!$this->canUpdate($request), 401, "Unauthorized");
        $ids = $request->input('ids');
        
        $this->validate($request, $this->updateRules);
        $class = $this->model;
        $results = $class::whereIn($this->id, $ids)->update($request->except('ids'));
        return  $class::whereIn($this->id, $ids)->get();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        abort_if(!$this->canDelete($request, $id), 401, "Unauthorized");
        $this->validate($request, $this->deleteRules);
        $class = $this->model;
        $class::destroy($id);
        return "true";
    }


    /**
     * Remove the many  resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyMany(Request $request)
    {
        abort_if(!$this->canDelete($request), 401, "Unauthorized");
        $ids = $request->input("ids");
        $this->validate($request, $this->deleteRules);
        $class = $this->model;
        $class::whereIn($this->id, $ids)->delete();
        return "true";
    }

    public function canList(Request $request)
    {
        return true;
    }
    public function canView(Request $request, $id)
    {
        return true;
    }
    public function canStore(Request $request)
    {
        return true;
    }
    public function canUpdate(Request $request, $id)
    {
        return true;
    }
    public function canDelete(Request $request, $id)
    {
        return true;
    }

    public function PreScope(Request $request, $query)
    {
        return $query;
    }
}
