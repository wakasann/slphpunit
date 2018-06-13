<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Fruit;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Transformers\FruitsTransformer;

class FruitsController extends Controller
{
    use Helpers;

    public function index(){
        $fruits = Fruit::all();
        return $this->response->collection($fruits, new FruitsTransformer);
    }

    public function show($id){
        $fruit = Fruit::where('id', $id)->first();

        if ($fruit) {
            return $this->response->item($fruit, new FruitsTransformer);
        }

        return $this->response->errorNotFound();
    }

    public function store(Requests\StoreFruitRequest $request)
    {
        if (Fruit::Create($request->all())) {
            return $this->response->created();
        }

        return $this->response->errorBadRequest();
    }

    /**
     * Remove the specified fruit.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fruit = Fruit::find($id);

        if ($fruit) {
            $fruit->delete();
            return $this->response->noContent();
        }

        return $this->response->errorBadRequest();
    }
}
