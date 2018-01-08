<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\BookModel;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = $request->input('type');
        $name = $request->input('name');
        $books = BookModel::where('pid', 0);
        if ($type) {
            $books = $books->where('id', $type);
        }
        if ($name) {
            $books = $books->with(['children' => function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            }]);
        } else {
            $books = $books->with('children');
        }
        $books = $books->orderBy('rank')->get();
        $types = BookModel::where('pid', 0)->get();
        $params = ['type' => $type, 'name' => $name];

        return view('web.book.index', compact('books', 'types', 'params'));
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        ini_set('memory_limit', 1024 * 1024 * 500);

        $book = BookModel::find($id);
        if (!$book->toArray() || !$book->path) {
            return response()->json(['state' => 'error', 'message' => '书籍不存在！']);
        }
        $cate = BookModel::where('id', $book->pid)->first();
        if (!$cate->toArray() || !$cate->path) {
            return response()->json(['state' => 'error', 'message' => '书籍不存在！']);
        }
        $book->increment('download');
        $filename = base_path() . $cate->path . $book->path;
        $type = '.' . pathinfo($book->path, PATHINFO_EXTENSION);

//        文件的类型
        header('Content-type: ' . config('mime')[$type]);//application/pdf
//        下载显示的名字
        header('Content-Disposition: attachment; filename="' . $book->name . $type . '"');
        readfile("$filename");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
