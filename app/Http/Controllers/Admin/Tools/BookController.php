<?php

namespace App\Http\Controllers\Admin\Tools;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper\ArrayHelper;
use App\Model\BookModel;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $names = array();
        $html = '';
        $books = BookModel::where('pid', 0)->with('children')->orderBy('rank')->get();

        if ($books->toArray()) {
            foreach ($books as $book) {
                $names[$book['id']] = $book['name'];
            }
            $html .= ArrayHelper::arrayToHtmlBook($books);
        }

        return view('admin.tools.book.index', compact('html', 'names'));
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
        $name = $request->input('name');
        $pid = $request->input('pid');
        $path = $request->input('path');
        $size = $request->input('size');
        $cloud = $request->input('cloud');
        $password = $request->input('password');
        $video = $request->input('video');

        if ($pid) {
            $cate = BookModel::where('id', $pid)->first();
            if (!$cate) {
                $pid = 0;
            }
        }
        if (mb_strlen($name) > 50) {
            return response()->json(['state' => 'error', 'message' => '类名/书名超出长度！']);
        } elseif (mb_strlen($path) > 255) {
            return response()->json(['state' => 'error', 'message' => '路径超出长度！']);
        } else {
            if (BookModel::where('name', $name)->first()) {
                return response()->json(['state' => 'error', 'message' => '类名/书名已存在！']);
            } else {
                $realPath = base_path() . $path;
                if (!$pid && !is_dir($realPath)) {
                    mkdir($realPath, 0777, true);
                    chmod($realPath, 0777);
                }
                $book = new BookModel();
                $book->pid = $pid;
                $book->name = $name;
                if ($path) {
                    $book->path = $path;
                }
                if ($size) {
                    $book->size = $size;
                }
                if ($cloud) {
                    $book->cloud = $cloud;
                }
                $book->password = $password;
                $book->video = $video;
                $book->save();

                return response()->json(['state' => 'success', 'message' => '分类/书籍添加成功！']);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $type = $request->input('type');

        if ($type == 'rank') {
            $list = json_decode($request->input('list'), true);
            if (is_array($list)) {
                $i = 1;
                foreach ($list as $book) {
                    foreach ($book as $value) {
                        if (is_integer($value)) {
                            BookModel::where('id', $book['id'])->update(['pid' => 0, 'rank' => $i]);
                            ++$i;
                        }
                        if (is_array($value)) {
                            foreach ($value as $child) {
                                BookModel::where('id', $child['id'])->update(['pid' => $book['id'], 'rank' => $i]);
                                ++$i;
                            }
                        }
                    }
                }
            }

            return response()->json(['state' => 'success', 'message' => '分类/书籍排序修改成功！']);
        } else {
            $pid = $request->input('pid');
            $name = $request->input('name');
            $path = $request->input('path');
            $size = $request->input('size');
            $cloud = $request->input('cloud');
            $password = $request->input('password');
            $video = $request->input('video');

            if (mb_strlen($name) > 50) {
                return response()->json(['state' => 'error', 'message' => '类名/书名超出长度！']);
            } elseif (mb_strlen($path) > 255) {
                return response()->json(['state' => 'error', 'message' => '路径超出长度！']);
            } else {
                $book = BookModel::where('name', $name)->first();
                if ($book && $book->id != $id) {
                    return response()->json(['state' => 'error', 'message' => '分类/书籍名称已存在！']);
                } else {
                    $realPath = base_path() . $path;
                    if (!$pid && !is_dir($realPath)) {
                        mkdir($realPath, 0777, true);
                        chmod($realPath, 0777);
                    }
                    if (!$book) {
                        $book = BookModel::find($id);
                    }

                    $book->pid = $pid;
                    $book->name = $name;
                    $book->path = $path;
                    $book->size = $size;
                    $book->cloud = $cloud;
                    $book->password = $password;
                    $book->video = $video;
                    $book->save();

                    return response()->json(['state' => 'success', 'message' => '分类/书籍修改成功！']);
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = BookModel::with('children')->find($id);
        if (!$book) {
            return response()->json(['state' => 'error', 'message' => '分类/书籍不存在！']);
        }
        if (count($book->children->toArray())) {
            return response()->json(['state' => 'error', 'message' => '分类下还有书籍，不允许删除！']);
        }
        if ($book->delete()) {
            return response()->json(['state' => 'success', 'message' => '分类/书籍删除成功！']);
        }

        return response()->json(['state' => 'error', 'message' => '分类/书籍删除失败！']);
    }
}
