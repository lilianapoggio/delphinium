<?php namespace Delphinium\Iris\Components;

use Delphinium\Iris\Classes\Iris as IrisClass;
use Cms\Classes\ComponentBase;
use Delphinium\Raspberry\Classes\Api;
use Delphinium\Blackberry\Models\User;

class IrisManager extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Manager for Iris Sunburst Chart',
            'description' => 'Provides parent-child relationship manager for an iris sunburst chart.'
        ];
    }
    
    public function onRun()
    {	
//        \Cache::flush();
        $this->addJs('/plugins/delphinium/iris/assets/javascript/jquery.min.js');
        $this->addJs('/plugins/delphinium/iris/assets/javascript/d3.v3.min.js');
        $this->addJs('/plugins/delphinium/iris/assets/javascript/jquery.nestable.js');
    	$this->addCss('/plugins/delphinium/iris/assets/css/nestable.css');
    	$this->addCss('/plugins/delphinium/iris/assets/css/font-awesome.css');	
    	$this->addCss('/plugins/delphinium/iris/assets/css/irismanager.css');		
        
        $this->addJs('/plugins/delphinium/iris/assets/javascript/sunburst.manager.js');
//        $this->addJs('/plugins/delphinium/iris/assets/javascript/manager2.js');
		
	$iris = new IrisClass();
        
        //session has aleady been started in LTI Component
        session_start();
        
        if(isset($_SESSION['courseID']))
        {
            $encryptedToken = $_SESSION['userToken'];
        
            $decrypted =$encryptedToken;//\Crypt::decrypt($encryptedToken);     
            $courseId = $_SESSION['courseID'];
            
            $val = $this->property('cacheTime');
            $moduleData = $iris->getModules($courseId, $decrypted, $val, false, null);

            $this->page['courseId'] = $courseId;

            $result = $this->buildTree($moduleData);

            $tempArray =array();

            if(count($result)<1) //there weren't any parent-child relationships
            {
                $tempArray = $moduleData;
            }
            else
            {
                $tempArray = $result;
            }

           $output =array();

            $this->page['moduleData'] = json_encode($tempArray);
            
        }
        else
        {
            var_dump("No active session");
        }
        
    }
    
     public function defineProperties()
    {
        return [
            'cacheTime' => [
                'title'              => 'Cache Time',
                'description'        => 'For how long should we cache Iris\' data (mins)?',
                'type'               => 'string',
                'default'            => 20,
                'validationPattern'  => '^[0-9]*$',
                'validationMessage'  => 'The number you entered is invalid'
            ]
            
        ];
    }
    
    public function onSaveModules()
    {
    	$iris = new IrisClass();
    	$list = post('listOrder',[]);
    	
    	
    	$array = json_decode($list);
    	
    	//this list contains the multi-dimensional list of parent-child relationships. Need to flatten it
    	$flatArray = $this->recursive($array);
    	
    	session_start();
        $courseId = $_SESSION['courseID'];
        
        $val = $this->property('cacheTime');
	$iris->saveIrisModules($flatArray, $courseId, $cacheTime);
    }
    
    public function onUpdateModule()
    {
            $api = new Api();
            $moduleId = 380199;
            $keyValueParams =  array(
            "name" => 'trial'
            );
            //"{\"name\" : \"Attendance\/Adjustments\" , \"kind\" : \"attend\" }"
            $val = $this->property('cacheTime');
            return $api->updateModule($moduleId, $keyValueParams,$cacheTime);

    }
    
    
    private function buildTree(array &$elements, $parentId = 1) {
        $branch = array();

        foreach ($elements as $key=>$module) {
//            print "<pre>";
//            print_r($module);
//            print "</pre>";
            if ($module['parentId'] == $parentId) {

                $children = $this->buildTree($elements, $module['moduleId']);
                if ($children) {
                    $module['children'] = $children;
                }
//                $branch[$module['moduleId']] = $module;
                $branch[] = $module;
                unset($elements[$module['moduleId']]);
            }
        }

        return $branch;

    }
 
    
    public function onAddTag()
    {
            $api = new Api();
            
            $contentId = 49051682;
            $tags = "li,lo";

            return $api->getTags($contentId);
    }
    
    public function onTest()
    {
//        $url = 'https://uvu.instructure.com/api/v1/courses/343331/modules?include[]=items&include[]=content_details&access_token=14~U2NLr7L2YmFsapN53ovxT6kvK4eToJL8LvuO2QZj1j8XAMLIlM1Yokz8CtKL8gxY&per_page=5000';
        
        $studentId = 1489289;
//        $userCheck = User::where('user_id',$studentId)->first();
//        $userCheck->delete();
        
        
        
        $courseId = 343331;
//        $contentId = 1660429;
//        $token = '14~Am18zfhe3qNoFuH3mXuGFPqe14SknkLq6qMbEchnkzPFEolaRvwoxvEXMbzQAojN';
//        $url = 'https://uvu.instructure.com/api/v1/courses/'.$courseId.'/modules?include[]=items&include[]=content_details&access_token='.$token.'&per_page=5000';
//        
        $api = new Api();
        $token = '14~U2NLr7L2YmFsapN53ovxT6kvK4eToJL8LvuO2QZj1j8XAMLIlM1Yokz8CtKL8gxY';
        $url = 'https://uvu.instructure.com/api/v1/courses/'.$courseId.'/modules?include[]=items&include[]=content_details&access_token='.$token.'&per_page=5000';
        $data = $api->getModules($url, $courseId, false, 0, null);
        
        var_dump($data);
//        $data = $api->addTags(49051682, json_encode($arr), $courseId);
//        $tag = array("Two");
//        $arr = json_encode($tag);
//        $data = $api->addTags(15056, $arr, 343331);
//        $data = $api->getAvailableTags($courseId);
//        
//        var_dump($data);
//        return;
		
        
        
//        $api = new Api();
//        $data = $api->getModuleStates(343331, 1489289);
//        var_dump($data);
//        $var = $api->getApiModules($url, $courseId, true);
        
//        $api->deleteTag(49051680, "Test");
    }
    
}