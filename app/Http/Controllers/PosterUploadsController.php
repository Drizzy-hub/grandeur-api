<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Poster;
use App\Models\PosterUploads;
use Validator;

class PosterUploadsController extends Controller
{
    
    //
    public function __construct()
    {
        
    }

    /**
     * Upload task with files
     * 
     * @return Illuminate\Http\JsonResponse
     * 
     */
    public function uploadTask(Request $request)
    {
        
        $validator = Validator::make($request->all(),[
            'title' => 'required|string',
            'desc' => 'required|string',
            'file_name' => 'required',
            'file_name.*' => 'required|mimes:doc,docx,pdf|max:2048',
            'budget' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ]);
        }
        // get the details of the current user
        $user = auth("poster")->user();

        // generate new file id
        $fileid = $this->NewGuid();
        $matricNo = $user->matricNo;
        $email = $user->email;
        $title = $request->title;
        $desc = $request->desc;
        $trackid = $user->id;
        $budget = $request->budget;
        $deadline = $request->deadline;
        $name = "";

        $filenames = "";
        /**
         * Check if a file has been selected 
         * 
         * upload each file selected
         */
        if($request->file('file_name')){
            foreach ($request->file_name as $file) {
                // get the file basename
                $name = $fileid.$file->getClientOriginalName();
                $file->storeAs("public/uploadedFiles/",$name);
                $filenames .= $name.";";
                
            }
            /**
             * Save each file into the database with the same owner details
             * matricNo, fileid
             */
            
            $data = new PosterUploads;
            $data->file_id = $fileid;
            $data->matricNo = $matricNo;
            $data->email = $email;
            $data->file_name = $filenames;
            $data->title = $title;
            $data->desc = $desc;
            $data->deadline = $deadline;
            $data->budget = $budget;
            $data->save();

            // return json response
            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully',
                'files' => $filenames, // send filenames with response
            ]);
        }
    }

    /**
     * Get the details of uploaded tasks by user
     * 
     * @return  Illuminate\Http\JsonResponse
     */

     public function uploadedTasks(){
         // get the curre
        $user = auth("poster")->user();
        // get all the uploaded tasks where email is same as Authenticated user
        $tasks = PosterUploads::all()->where('email', $user->email);
        $files = "";
        $detail = array();
        // $task_files = [];
        // // $numFile = [];


        // foreach ($tasks as $key => $value) {
        //     $nArry = array('file_id' => $value->file_id,'file_name' => $value->file_name );
        //     array_push($task_files, $nArry);
        // }
        foreach ($tasks as $key => $task) {
            // if(in_array($task->file_id, $numFile))
            // {
            //     for ($i=0; $i < count($numFile); $i++) { 
            //         if ($detail[$i]['file_id'] == $task->file_id) {
            //             $detail[$i]['file_name'] = $detail[$i]['file_name'].";".$task->file_name;
            //         }
            //     }
            // }else{

                $detail[$key] =[
                    'id' => $task->id,
                    'matricNo' => $task->matricNo,
                    'file_id' => $task->file_id,
                    'title' => $task->title,
                    'desc' => $task->desc,
                    'deadline' => $task->deadline,
                    'pay_status' => $task->pay_status,
                    'date_upld' => $task->date_upload,
                ];
                
                // $numFile[] = $task->file_id;
            // }
            
            
            $files .= $task->file_name;
        }
        // $det = array_merge($detail[0],$detail[1]);
        return response()->Json([
            'success' => true,
            'message' => "My Uploaded Tasks",
            'data' => $detail
            ]);
     }

    /**
     * Get a task with provided file id
    *
    * @return Illuminate\Http\JsonResponse
    */
    public function ATask($fileid)
    {
        // get the task details with the specified id
        $task = PosterUploads::where('file_id', $fileid)->first();

        if (is_null($task)) {
            return response()->json([
                'success' => false,
                'message' => 'Task('.$fileid.') not found',
            ]);
        }
        return response()->Json([
            'success' => true,
            'message' => "Task found",
            'data' => $task
        ]);
    }

    /**
     *  Generate Unique ID
     */

    public function NewGuid() {
        $s = (uniqid(rand(),true)); 
        $guidText = substr($s,0,3) ; 
        return $guidText;
    }

}
