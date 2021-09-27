<?php


namespace frontend\controllers;


use backend\models\AgentData;
use backend\models\SalesAgent;
use backend\models\Subscriber;
use common\models\LoginForm;
use common\models\User;

use kartik\mpdf\Pdf;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use SimpleXMLElement;



class ApiController extends Controller
{
    public function behaviors()
    {

        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
            'auth' => function ($username, $password) {
                $user = User::findByUsername($username);
                return $user->validatePassword($password)
                    ? $user
                    : null;
            }
        ];
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => \yii\web\Response::FORMAT_JSON,
            ],
        ];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['dashboard', 'login'],
            'rules' => [
                [
                    'actions' => ['dashboard', 'login'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        return $behaviors;


    }

    public function actionLogin()
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;
        $response = [

            'success'=>true,
            'access_token' => Yii::$app->user->identity->getAuthKey(),
            'username' => Yii::$app->user->identity->username,
//            'user_id' => Yii::$app->user->identity->user_id,
            'status' => Yii::$app->user->identity->status,

        ];
        return $response;

    }

    public function actionServices()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;




        $services = ServiceType::getAll();
        if ($services != null) {

            $data = ['success' => true, 'data' => $services];;
            return $data;


        } else {
            $data = ['success' => false, 'data' => 'No Services'];
            return $data;
        }


    }

    public function actionRegister()
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        $data = file_get_contents('php://input');
        if ($data != null) {
            $model = new AgentData();
            $model->data = $data;
            if($model->save(false)){
                $Json = $model['data'];

                //converts json data to array data
                $decodedSales = json_decode($Json, true);



                //Extracting API Request data
                $agent_name =  $decodedSales['agent_name'];
                $agent_address =  $decodedSales['email_address'];
                $agent_code =  $decodedSales['agent_code'];
                $agent_phone =  $decodedSales['mobile_number'];

              $agent = new SalesAgent();
              $agent->agent_name =$agent_name;
              $agent->email_address =$agent_address;
              $agent->agent_code =$agent_code;
              $agent->mobile_number =$agent_phone;

              $agent->save(false);
              return 'Success';

            }
        }

    }


    public function actionRegistrationSubscriber()
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        $data = file_get_contents('php://input');
        if ($data != null) {
            $model = new AgentData();
            $model->data = $data;
            if($model->save(false)){
                $Json = $model['data'];

                //converts json data to array data
                $decodedSales = json_decode($Json, true);



                //Extracting API Request data
                $agent_name =  $decodedSales['agent_name'];
                $agent_address =  $decodedSales['email_address'];
                $agent_code =  $decodedSales['agent_code'];
                $agent_phone =  $decodedSales['mobile_number'];
                $agent_date =  $decodedSales['date_of_birth'];

                $agent = new Subscriber();
                $agent->full_name =$agent_name;
                $agent->date_of_birth =$agent_date;
                $agent->email_address =$agent_address;
                $agent->agent_code =$agent_code;
                $agent->mobile_number =$agent_phone;

                $agent->save(false);
                return 'Success';

            }
        }
    }








}