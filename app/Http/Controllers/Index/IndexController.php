<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;



class IndexController extends Controller
{
    protected $category=['未知','最新','国语','微电影','经典高清','动画电影','3D电影','国剧','日韩剧','欧美剧','综艺'];
    function addOneDy(Request $request){
        $params = $request->all();
        Log::info('ling',[1]);
        Log::info('ling',$params);
        $data = [];
        $data['category'] = $this->category[$params['category_id']];
        $data['title'] = $params['title'] ?? "";
        $data['name'] = $params['name'];
        $data['image_url'] = $params['image_url'] ?? "";
        $data['content'] = $params['content'];
        $data['update_time'] = strtotime($params['update_time']);

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
                    $data['dy_id'] = $update->id;
                    $this->pushContent($data);
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
            $data['dy_id'] = $list_id;
            $this->pushContent($data);
            return response()->json(['message' => ' 数据新增 成功 ！！', 'result' => 200]);
        }else{
            DB::rollBack();
            Log::info('ling', ['数据增加 失败 ！！']);
            Log::info('ling', $params);
            return response()->json(['message' => ' 数据增加 失败 ！！', 'result' => 400]);
        }
    }
    function pushContent($data){
        try{
            $client = new Client();
            $request = new \GuzzleHttp\Psr7\Request('POST', 'http://movie.vbaodian.cn/ling/addContent/dy/one');
        }catch (\Exception $e) {
            Log::info("error: 创建 http client 失败 ！！！！");
        }
        try{
            $res = $client->send($request,['form_params'=>$data]);
            Log::info("result : {$res->getBody()}");
        }catch (\Exception $e) {
            Log::info('error: 请求 服务器失败 http://movie.vbaodian.cn/ling/addContent/dy/one');
        }
    }

    function test(){
        return view("test");
    }

    function dataList(Request $request){

        $params = $request->all();
        Log::info('ling', $params);

        $list = DB::table('dy_list')
            ->where(function ($query) use ($params){

                if (!empty($params['id'])) {
                    $query->where('id', '=', $params['id']);
                }

                if (!empty($params['title'])) {
                    $query->where('title', 'like', '%'.$params['title'].'%');
                }
                if (!empty($params['category_id'])){
                    $query->where('category_id', '=', $params['category_id']);
                }

                if (!empty($params['updateTimeStart']) && !empty($params['updateTimeEnd'])) {
                    $query->whereBetween('update_time',[date('Y-m-d 00:00:00',strtotime($params['updateTimeStart'])), date('Y-m-d 23:59:59',strtotime($params['updateTimeEnd']))]);
                }elseif (!empty($params['updateTimeStart'])) {
                    $query->where('update_time', '>', date('Y-m-d 00:00:00',strtotime($params['updateTimeStart'])));
                }elseif (!empty($params['updateTimeEnd'])){
                    $query->where('update_time', '<', date('Y-m-d 23:59:59',strtotime($params['updateTimeEnd'])));
                }

            })
            ->select('id','title','name','update_time','category_id')
            ->orderBy('update_time','desc')
            ->paginate(20);
        return view("admin.list")
            ->with('list', $list)
            ->with('params', $params)
            ->with('category', $this->category)
            ;
    }

    function dataDetail($id){

        $data = [];
        if ($id) {
            $data = DB::table('dy_list')->where('id', $id)->first();
            $content = DB::table("dy_content_0{$data->content_table_tag}")->where('id', $data->content_id)->first();
            $data->content = $content->content;
        }
        return view("admin.edit")
            ->with('data', $data)
            ->with('category', $this->category);
    }
}
