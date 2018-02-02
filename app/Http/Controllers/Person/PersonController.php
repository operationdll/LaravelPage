<?php

namespace App\Http\Controllers\Person;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Service\PersonalService;

class PersonController extends Controller{

    private $personalService;

    public function __construct(PersonalService $personalService)
    {
        $this->personalService = $personalService;
    }


    public function getList(){
        $name = null;
        if(Input::all()){
            $input = Input::all();
            if(isset($input['name'])){
                $name = $input['name'];
            }
        }
        return view('person.list',['persons'=>$this->personalService->getPersons($name)]);
    }

    public function detail(){
        $person = null;
        if(Input::all()){
            $input = Input::all();
            $person = DB::table('user')->where('id',$input['id'])->first();
        }
        return view('person.detail',['person'=>$person]);
    }

    public function statistical(){
        $person = null;
        if(Input::all()){
            $input = Input::all();
            $stime = strtotime($input['stime']);
            $etime = strtotime($input['etime']);

            //练习题总数(The total number of exercises)
            $sql = "select".
                " t1.nickname,".
                " count(*) total".
                " from user t1 left join course_task_result t2 on t1.id = t2.userId".
                " left join testpaper_result_v8 t3 on t3.courseId = t2.courseId".
                " where t1.id =".$input['uid'].
                " and t2.finishedTime>=".$stime.
                " and t2.finishedTime<=".$etime.
                " group by t1.nickname";
            $r1 = DB::select($sql);

            //完成的练习总数(The total number of exercises completed)
            $sql = "select".
                " t1.nickname,".
                " count(*) total".
                " from user t1 left join course_task_result t2 on t1.id = t2.userId".
                " left join testpaper_result_v8 t3 on t3.courseId = t2.courseId".
                " where t1.id =".$input['uid'].
                " and t2.finishedTime>=".$stime.
                " and t2.finishedTime<=".$etime.
                " and t3.status='finished'".
                " group by t1.nickname";
            $r2 = DB::select($sql);

            //未完成的练习数
            $sql = "select".
                " t1.nickname,".
                " count(*) total".
                " from user t1 left join course_task_result t2 on t1.id = t2.userId".
                " left join testpaper_result_v8 t3 on t3.courseId = t2.courseId".
                " where t1.id =".$input['uid'].
                " and t2.finishedTime>=".$stime.
                " and t2.finishedTime<=".$etime.
                " and t3.status <>'finished'".
                " group by t1.nickname";
            $r3 = DB::select($sql);

            //完成练习的平均分
            $sql = "select".
                " t1.nickname,".
                " ROUND(avg(t3.score),2) score".
                " from `user` t1 left join `course_task_result` t2 on t1.id = t2.userId".
                " left join testpaper_result_v8 t3 on t3.courseId = t2.courseId".
                " where t1.id =".$input['uid'].
                " and t2.finishedTime>=".$stime.
                " and t2.finishedTime<=".$etime.
                " and t3.status='finished'".
                " group by t1.nickname";
            $r4 = DB::select($sql);

            //完成练习的平均时间
            $sql = "select".
                " t1.nickname,".
                " ROUND(avg(t3.endTime-beginTime)) avgTime".
                " from `user` t1 left join `course_task_result` t2 on t1.id = t2.userId".
                " left join testpaper_result_v8 t3 on t3.courseId = t2.courseId".
                " where t1.id =".$input['uid'].
                " and t2.finishedTime>=".$stime.
                " and t2.finishedTime<=".$etime.
                " and t3.status='finished'".
                " group by t1.nickname";
            $r5 = DB::select($sql);
            $r5 = date("H:i",$r5[0]->avgTime);

            //完成练习的最好分数
            $sql = "select".
                    " t1.nickname,".
                    " max(t3.score) bscore".
                    " from `user` t1 left join `course_task_result` t2 on t1.id = t2.userId".
                    " left join testpaper_result_v8 t3 on t3.courseId = t2.courseId".
                    " where t1.id =".$input['uid'].
                    " and t2.finishedTime>=".$stime.
                    " and t2.finishedTime<=".$etime.
                    " and t3.status='finished'".
                    " group by t1.nickname";
            $r6 = DB::select($sql);

            //完成练习的最差分数
            $sql = "select".
                    " t1.nickname,".
                    " min(t3.score) wscore".
                    " from `user` t1 left join `course_task_result` t2 on t1.id = t2.userId".
                    " left join testpaper_result_v8 t3 on t3.courseId = t2.courseId".
                    " where t1.id =".$input['uid'].
                    " and t2.finishedTime>=".$stime.
                    " and t2.finishedTime<=".$etime.
                    " and t3.status='finished'".
                    " group by t1.nickname";
            $r7 = DB::select($sql);

            //完成练习的错误题数
            $sql = "select".
                " t1.nickname,".
                " count(*) enum".
                " from `user` t1 left join `course_task_result` t2 on t1.id = t2.userId".
                " left join testpaper_result_v8 t3 on t3.courseId = t2.courseId".
                " left join testpaper_item_result_v8 t4 on t3.id=t4.resultId".
                " where t1.id =".$input['uid'].
                " and t2.finishedTime>=".$stime.
                " and t2.finishedTime<=".$etime.
                " and t2.status='finish'".
                " and t4.status in('wrong')".
                " group by t1.nickname";
            $r8 = DB::select($sql);

            //完成练习的未答题数
            $sql = "select".
                " t1.nickname,".
                " count(*) noAnswer".
                " from `user` t1 left join `course_task_result` t2 on t1.id = t2.userId".
                " left join testpaper_result_v8 t3 on t3.courseId = t2.courseId".
                " left join testpaper_item_result_v8 t4 on t3.id=t4.resultId".
                " where t1.id =".$input['uid'].
                " and t2.finishedTime>=".$stime.
                " and t2.finishedTime<=".$etime.
                " and t2.status='finish'".
                " and t4.status in('noAnswer')".
                " group by t1.nickname";
            $r9 = DB::select($sql);

            //完成练习的错误题类型
            $sql = "select".
                " distinct t5.categoryId etype".
                " from `user` t1 left join `course_task_result` t2 on t1.id = t2.userId".
                " left join testpaper_result_v8 t3 on t3.courseId = t2.courseId".
                " left join testpaper_item_result_v8 t4 on t3.id=t4.resultId".
                " left join question t5 on t5.courseId = t2.courseId".
                " where t1.id =".$input['uid'].
                " and t2.finishedTime>=".$stime.
                " and t2.finishedTime<=".$etime.
                " and t2.status='finish'".
                " and t4.status in('wrong')";
            $r10 = DB::select($sql);

            //完成练习的未答题类型
            $sql = "select".
                " distinct t5.categoryId ntype".
                " from `user` t1 left join `course_task_result` t2 on t1.id = t2.userId".
                " left join testpaper_result_v8 t3 on t3.courseId = t2.courseId".
                " left join testpaper_item_result_v8 t4 on t3.id=t4.resultId".
                " left join question t5 on t5.courseId = t2.courseId".
                " where t1.id =".$input['uid'].
                " and t2.finishedTime>=".$stime.
                " and t2.finishedTime<=".$etime.
                " and t2.status='finish'".
                " and t4.status in('noAnswer')";
            $r11 = DB::select($sql);

            //完成练习的difficult的错题
            $sql = "select".
                " count(*) dnum".
                " from `user` t1 left join `course_task_result` t2 on t1.id = t2.userId".
                " left join testpaper_result_v8 t3 on t3.courseId = t2.courseId".
                " left join testpaper_item_result_v8 t4 on t3.id=t4.resultId".
                " left join question t5 on t5.courseId = t2.courseId".
                " where t1.id =".$input['uid'].
                " and t2.finishedTime>=".$stime.
                " and t2.finishedTime<=".$etime.
                " and t2.status='finish'".
                " and t4.status in('wrong')".
                " and t5.difficulty='difficulty'";
            $r12 = DB::select($sql);

            //完成练习的normal难度的错题
            $sql = "select".
                " count(*) nnum".
                " from `user` t1 left join `course_task_result` t2 on t1.id = t2.userId".
                " left join testpaper_result_v8 t3 on t3.courseId = t2.courseId".
                " left join testpaper_item_result_v8 t4 on t3.id=t4.resultId".
                " left join question t5 on t5.courseId = t2.courseId".
                " where t1.id =".$input['uid'].
                " and t2.finishedTime>=".$stime.
                " and t2.finishedTime<=".$etime.
                " and t2.status='finish'".
                " and t4.status in('wrong')".
                " and t5.difficulty='normal'";
            $r13 = DB::select($sql);
        }
        return view('person.statistical',['r1'=>$r1[0],'r2'=>$r2[0],'r3'=>$r3[0],'r4'=>$r4[0],
            'r5'=>$r5,'r6'=>$r6[0],'r7'=>$r7[0],'r8'=>$r8[0],'r9'=>$r9[0],'r10'=>$r10,'r11'=>$r11,'r12'=>$r12[0],'r13'=>$r13[0]]);
    }



}
