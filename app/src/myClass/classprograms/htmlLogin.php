<?php
namespace App\myClass\classprograms;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う

class htmlLogin extends AppController
{
     public function initialize()
    {
        parent::initialize();
        $this->Users = TableRegistry::get('Users');
    }

     public function inputstaffctp()
  	{
        $html =
                "<table><tbody style='background-color: #FFFFCC'>\n".
                "<tr><td width='280'><strong>社員コード</strong></td></tr>\n".
                "<tr><td style='border: 1px solid black'><input type='text' name=user_code required autofocus/></td></tr>\n".
        		    "</tbody></table>\n";

    		return $html;
    		$this->html = $html;
  	}

    public function inputstaffprogram($user_code)
   {
     $Users= $this->Users->find()->contain(["Staffs"])->where(['user_code' => $user_code, 'Users.delete_flag' => 0])->toArray();

     if(isset($Users[0])){

       $staff_id = $Users[0]["staff_id"];
       $staff_name= $Users[0]["staff"]["name"];

     }else{

       $staff_id = "no_staff";
       $staff_name= "no_staff";

     }

     $arraylogindate[] = $staff_id;
     $arraylogindate[] = $staff_name;

     return $arraylogindate;
   }

}

?>
