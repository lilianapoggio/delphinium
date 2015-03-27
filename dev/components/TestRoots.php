<?php namespace Delphinium\Dev\Components;

use Delphinium\Core\UpdatableObjects\Module;
use Delphinium\Core\UpdatableObjects\ModuleItem;
use Delphinium\Core\Roots;
use Delphinium\Core\RequestObjects\SubmissionsRequest;
use Delphinium\Core\RequestObjects\ModulesRequest;
use Delphinium\Core\RequestObjects\AssignmentsRequest;
use Delphinium\Core\RequestObjects\AssignmentGroupsRequest;
use Delphinium\Core\Enums\CommonEnums\ActionType;
use Delphinium\Core\Enums\ModuleItemEnums\ModuleItemType;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Cache;
use \DateTime;
use \DateInterval;
use Delphinium\Core\DB\DbHelper;

class TestRoots extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Test Roots',
            'description' => 'This component will test the Roots API'
        ];
    }
    
    public function onRun()
    {  
//        $this->refreshCache();
//        $this->test();
//        Cache::flush();
//        $this->testBasicModulesRequest();
//        $this->testUpdatingModuleItem();
//        $this->testUpdatingModule();
        
//        $this->testDeletingModuleItem();
//        $this->testDeletingModule();
        
//        $this->testAddingModule();
//        $this->testAddingModuleItem();
//        
//        $this->testingGettingAssignments();
//        $this->testGettingSingleAssignment();
        
//        $this->testAssignmentGroups();
//        $this->testSingleAssignmentGroup();
//        
//        $this->testGettingSingleSubmissionSingleUserSingleAssignment();
//        $this->testGettingAllSubmissionForSingleAssignment();
//        $this->testGettingMultipleSubmissionsForSingleStudent();
//        $this->testGettingMultipleSubmissionsAllStudents();
//        $this->testGettingMultipleSubmissionsMultipleStudents();
//        $this->testGettingSubmissions();
    }
    
    private function testBasicModulesRequest()
    {
        $req = new ModulesRequest(ActionType::GET);
        $req->moduleId = null;//456852;
        $req->includeContentDetails = true;
        $req->includeContentItems = true;
        $req->moduleItemId = null;//2869243;
        $req->params = null;
        
        $roots = new Roots();
        $res = $roots->modules($req);
        echo json_encode($res);
    }
    
    
    private function testUpdatingModule()
    {   
        //update a module (changing title and published to false)
        $req = new ModulesRequest(ActionType::PUT);
        $req->moduleId = 380199;
        $req->moduleItemId = null;
        $params = array("name"=>"New name","published"=>"true");
        $req->params = $params;
        
        $roots = new Roots();
        $res = $roots->modules($req);
    }
    
    private function testUpdatingModuleItem()
    {
        $req = new ModulesRequest(ActionType::PUT);
        $req->moduleId = 456852;
        $req->moduleItemId = 2881776;
        $params = array("title"=>"Subheader","published"=>"true", "external_url"=>"https://www.google.com");
        $req->params = $params;
        
        $roots = new Roots();
        $res = $roots->modules($req);
    }
    
    
    private function testDeletingModuleItem()
    {
        $req = new ModulesRequest(ActionType::DELETE);
        $req->moduleId = 456847;
        $req->includeContentDetails = true;
        $req->includeContentItems = true;
        $req->moduleItemId = 2881762;
        
        $roots = new Roots();
        $res = $roots->modules($req);
        echo json_encode($res);
    }
    
    private function testDeletingModule()
    {
        $req = new ModulesRequest(ActionType::DELETE);
        $req->moduleId = 456847;
//        $req->moduleItemId = 2870946;
        
//        \Cache::flush();
        $roots = new Roots();
        $res = $roots->modules($req);
        echo json_encode($res);
    }
    private function testAddingModule()
    {
        $name = "Module from API";
        
        $format = DateTime::ISO8601;
        $date = new DateTime("now");
        $date->add(new DateInterval('P1D'));
//        echo json_encode($date);
//        return;
        $unlock_at = $date;
        $prerequisite_module_ids =array("380199","380201");
        
//        $module = new Module($name, $published);
        $module = new Module($name, $unlock_at, $prerequisite_module_ids);
        
        $req = new ModulesRequest(ActionType::POST);
        $req->moduleId = null;
        $req->module = $module;
        
        $roots = new Roots();
        $res = $roots->modules($req);
        echo json_encode($res);
    }
    
    private function testAddingModuleItem()
    {
        $req = new ModulesRequest(ActionType::POST);
        $req->moduleId = 456845;
        
        $title = "Testing module Item from API";
        $modItemType = ModuleItemType::SUBHEADER;
        
        $page_url = "http://www.google.com";
        $moduleItem = new ModuleItem($title, $modItemType, null, $page_url);
        
        $req = new ModulesRequest(ActionType::POST);
        $req->moduleId = 456845;
        $req->moduleItem = $moduleItem;
        
        $roots = new Roots();
        $res = $roots->modules($req);
        echo json_encode($res);
    }
    
    private function testingGettingAssignments()
    {
        $req = new AssignmentsRequest(ActionType::GET);
        
        $roots = new Roots();
        $res = $roots->assignments($req);
        echo json_encode($res);
    }
    
    private function testGettingSingleAssignment()
    {
        $assignment_id = 1660430;
        $req = new AssignmentsRequest(ActionType::GET, $assignment_id);
        
        $roots = new Roots();
        $res = $roots->assignments($req);
        echo json_encode($res);
    }
    
    private function testAssignmentGroups()
    {
        $req = new AssignmentGroupsRequest(ActionType::GET, false, null);
        
        $roots = new Roots();
        $res = $roots->assignmentGroups($req);
        echo json_encode($res);   
    }
    
    private function testSingleAssignmentGroup()
    {
        $assignment_group_id = 378245;
        $req = new AssignmentGroupsRequest(ActionType::GET, true, $assignment_group_id);
        
        $roots = new Roots();
        $res = $roots->assignmentGroups($req);
        echo json_encode($res);
    }
    
    
    private function testGettingSingleSubmissionSingleUserSingleAssignment()
    {
        $studentIds = array(1489289);
        $assignmentIds = array(1660419);
        $multipleStudents = false;
        $multipleAssignments = false;
        $allStudents = false;
        $allAssignments = false;
        
        //can have the student Id param null if multipleUsers is set to false (we'll only get the current user's submissions)
        $req = new SubmissionsRequest(ActionType::GET, $studentIds, $allStudents, 
                $assignmentIds, $allAssignments, $multipleStudents, $multipleAssignments);
        
        $roots = new Roots();
        $res = $roots->submissions($req);
        echo json_encode($res);
    }
    
    private function testGettingAllSubmissionForSingleAssignment()
    {
        $studentIds = array(10733259,10733259);
        $assignmentIds = array(1660406);//array(1660419);
        $multipleStudents = true;
        $multipleAssignments = false;
        $allStudents = true;
        $allAssignments = false;
        
        //can have the student Id param null if multipleUsers is set to false (we'll only get the current user's submissions)
        $req = new SubmissionsRequest(ActionType::GET, $studentIds, $allStudents, 
                $assignmentIds, $allAssignments, $multipleStudents, $multipleAssignments);
        
        $roots = new Roots();
        $res = $roots->submissions($req);
        echo json_encode($res);
    }
    
    private function testGettingMultipleSubmissionsForSingleStudent()
    {
        $studentIds = array(10733259);
        $assignmentIds = array(1660419, 1660406, 1660412);
        $multipleStudents = false;
        $multipleAssignments = true;
        $allStudents = false;
        $allAssignments = false;
        
        //can have the student Id param null if multipleUsers is set to false (we'll only get the current user's submissions)
        
        $req = new SubmissionsRequest(ActionType::GET, $studentIds, $allStudents, 
                $assignmentIds, $allAssignments, $multipleStudents, $multipleAssignments);
        
        $roots = new Roots();
        $res = $roots->submissions($req);
        echo json_encode($res);
    }
    
    private function testGettingMultipleSubmissionsAllStudents()
    {
        $studentIds = null;
        $assignmentIds = array(1660419, 1660406, 1660412);
        $multipleStudents = true;
        $multipleAssignments = true;
        $allStudents = true;
        $allAssignments = false;
        
        //can have the student Id param null if multipleUsers is set to false (we'll only get the current user's submissions)
        
        $req = new SubmissionsRequest(ActionType::GET, $studentIds, $allStudents, 
                $assignmentIds, $allAssignments, $multipleStudents, $multipleAssignments);
        
        $roots = new Roots();
        $res = $roots->submissions($req);
        echo json_encode($res);
    }
        
    private function testGettingMultipleSubmissionsMultipleStudents()
    {//This throws an error because I'm not authorized to retrieve submissions in behalf of other students
        $studentIds = array(10733259,10733259);
        $assignmentIds = array(1660419, 1660406, 1660412);
        $multipleStudents = true;
        $multipleAssignments = true;
        $allStudents = false;
        $allAssignments = false;
        
        //can have the student Id param null if multipleUsers is set to false (we'll only get the current user's submissions)
        
        $req = new SubmissionsRequest(ActionType::GET, $studentIds, $allStudents, 
                $assignmentIds, $allAssignments, $multipleStudents, $multipleAssignments);
        
        $roots = new Roots();
        $res = $roots->submissions($req);
        echo json_encode($res);
    }
    
    private function refreshCache()
    {
        $moduleId = null;
        $includeContentDetails = true;
        $includeContentItems = true;
        $moduleItemId = null;
        $params = null;
        $refreshData = true;
        
        $req = new ModulesRequest(ActionType::GET, $moduleId, $moduleItemId, $includeContentItems, $includeContentDetails, $params, null, 
                null, $refreshData);
        
        $roots = new Roots();
        $res = $roots->modules($req);
        echo json_encode($res);
    }
    
    
    private function test()
    {
//        $format = DateTime::ISO8601;
//        $date = new DateTime("now");
////        $date->add(new DateInterval('P1D'));
//        
//        echo json_encode($date); 
        
        //"380199",
        $arr = array("380200","380201","380202","380203","380204","380205","380206","380207","380208","380209","380210","380211",
            "380212","380213","380214","380215","380216","380217","380218","380219","380220","380221","456852","456876","456877","456878");
        
        $dbHelper = new DbHelper();
        $dbHelper->qualityAssuranceModules(343331, $arr);
    }
    
}

