<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class PushContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:content';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'push dy content';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $category=['未知','最新','国语','微电影','经典高清','动画电影','3D电影','国剧','日韩剧','欧美剧','综艺'];
        $this->info('ling');

        $index_end = 22155;
//        $index_end = 100;
        $index_start = 1;
        try{
            $client = new Client();
            //$request = new Request('POST', 'http://movie.vbaodian.cn/ling/addContent/dy/one');
            //$request = new Request('POST', 'http://movie.vbaodian.cn/ling/addContent/dy/two');
            $request = new Request('POST', 'http://localhost:8087/ling/addContent/dy/one');
        }catch (\Exception $e) {
            $this->info("error: 创建 http client 失败 ！！！！");
            exit();
        }

        while ($index_end >= $index_start) {
            $this->info($index_start);
            $list = DB::table('dy_list')->where('id', $index_start)->first();

            if ($list) {
                $content = DB::table("dy_content_0{$list->content_table_tag}")->where('id',$list->content_id)->first();
            }

            if ($content  && $list) {
                $data = [];
                $data['dy_id'] = $list->id;
                $data['category'] = $category[$list->category_id];
                $data['title'] = $list->title;
                $data['name'] = $list->name;
                $data['image_url'] = $list->image_url;
                $data['content'] = $content->content;
                $data['update_time'] = strtotime($list->update_time);

                $res = $client->send($request,['form_params'=>$data]);
                $this->info($res->getStatusCode());
            }
            $index_start += 1;
        }

    }
}
