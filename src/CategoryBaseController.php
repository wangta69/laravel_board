<?php
namespace Wangta69\Bbs;

use Illuminate\Http\Request;

use Route;
use View;
use Validator;

use Wangta69\Bbs\Models\BbsCategories as Category;
// use Wangta69\Bbs\Models\Role;
// use Wangta69\Bbs\BbsService;



class CategoryBaseController extends \App\Http\Controllers\Controller {

    protected $bbsSvc;
    protected $cfg;
    public function __construct() {}

    public function addCategory(Request $request, $tableId) {

        // 카테고리 테이블의 bbs_table_id에서 max order를 구한 후 + 1하여 저장한다.
        $max = Category::where('bbs_table_id', $tableId)->max('order');

        $order = $max ? $max + 1 : 1;

        $category = new Category;
        $category->bbs_table_id = $tableId;
        $category->name = $request->category;
        $category->order = $order;
        $category->save();
        return response()->json([
            'error' => false,
            'id' => $category->id
        ], 200);

    }

    /**
     *  $category 를 입력받아서 현재 카테고리가 속한 table_id를 구한 후 방향에 딸 새로 정렬한다.
     */
    public function updateOrder($category, $direction, Request $request) {
        $cat = Category::find($category); // $cat->order;
    //    $categories = Category::where('bbs_table_id', $cat->bbs_table_id);
        switch ($direction) {
            case 'up': // $cat->order 보다 하나 높은 것은 다운 시킨다.
                $prev = Category::where('bbs_table_id', $cat->bbs_table_id)
                    ->where('order', $cat->order - 1)
                    ->first();

                    if ($prev) {
                        $prev->increment('order');
                        $cat->decrement('order');
                    }
                break;
            case 'down': // $cat->order 보다 하나 낮은 것은 업 시킨다.
                $next = Category::where('bbs_table_id', $cat->bbs_table_id)
                    ->where('order', $cat->order + 1)
                    ->first();

                    if ($next) {
                        $next->decrement('order');
                        $cat->increment('order');
                    }
                break;
        }

        return response()->json([
            'error' => false
        ], 200);

    }

    public function deleteCategory($category, Request $request) {
        $cat = Category::find($category); // $cat->order;
        $cat->delete();

        $categories = Category::where('bbs_table_id', $cat->bbs_table_id)->orderBy('order')->get();

        // 현재 카테고리는 삭제하고 현재 카테고리보다 order가 높은 것은 order를 다시 배열한다.
        $order = 1;
        foreach($categories as $c) {
            $cat1 = Category::find($c->id);
            $cat1->order = $order;

            $cat1->save();
            $order++;
        }

        return response()->json([
            'error' => false
        ], 200);

    }

}
