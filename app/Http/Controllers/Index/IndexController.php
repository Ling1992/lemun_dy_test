<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Log;



class IndexController extends Controller
{
    function addOneDy(Request $request){
        $params = $request->all();
        Log::info('ling',[1]);
        Log::info('ling',$params);


        $update = DB::table('dy_list')
            ->where('name','=',$params['name'])
            ->where('category_id', '=', $params['category_id'])
            ->orderBy('id', 'desc')
            ->first();

        if ($update) {
            if ($update->title == $params['title']){
                return response()->json(['message' => '数据重复','result' => 222]);
            }else{
                DB::beginTransaction();
                $update_1 = DB::table('dy_list')
                    ->where('id',$update->id)
                    ->update([
                        'title' => $params['title'],
                        'update_time'=>$params['update_time'],
                    ]);
                $update_2 = DB::table("dy_content_0{$update->content_table_tag}")
                    ->where('id',$update->content_id)
                    ->update(['content'=>$params['content']]);
                if ($update_1 && $update_2) {
                    DB::commit();
                    Log::info('ling', ['数据更新 成功 ！！']);
                    return response()->json(['message' => ' 数据更新 成功 ！！', 'result' => 201]);
                }else{
                    Log::info('ling', ['数据更新 失败 ！！']);
                    DB::rollBack();
                    return response()->json(['message' => ' 数据更新 失败 ！！', 'result' => 401]);
                }
            }
        }


        $content_table_tag = mt_rand(1, 4);
        DB::beginTransaction();
        $content_id = DB::table("dy_content_0{$content_table_tag}")->insertGetId(
            ['content' => $params['content']]
        );
        Log::info('ling',[2]);
        $list_id = DB::table("dy_list")->insertGetId(
            [
                'title' => $params['title'],
                'name' => $params['name'] ?? "",
                'image_url' => $params['image_url'] ?? "",

                'category_id' => $params['category_id'],
                'content_id' => $content_id,
                'content_table_tag' => $content_table_tag,
                'update_time'=>$params['update_time'],
            ]
        );
        Log::info('ling',[3]);
        if ($content_id && $list_id) {
            DB::commit();
            Log::info('ling', ['数据新增 成功 ！！']);
            return response()->json(['message' => ' 数据新增 成功 ！！', 'result' => 200]);
        }else{
            DB::rollBack();
            Log::info('ling', ['数据增加 失败 ！！']);
            Log::info('ling', $params);
            return response()->json(['message' => ' 数据增加 失败 ！！', 'result' => 400]);
        }
    }
    function pushContent(){
        $category=['未知','最新','国语','微电影','经典高清','动画电影','3D电影','国剧','日剧','欧美剧','综艺'];
        echo $category[1];
    }
}