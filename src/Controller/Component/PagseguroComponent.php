<?php
namespace Pagseguro\Controller\Component;
use Cake\Controller\Component;
use Cake\Routing\Router;

class PagseguroComponent extends Component {

    const PAC = 1;
    const SEDEX = 2;
    const NOT_SPECIFIED = 3;

    public $request_pg;
    public $uid;

    public function initialize(array $config) {
        parent::initialize($config);
        $this->uid = uniqid();
        $this->request_pg = new \PagSeguroPaymentRequest;
        $this->request_pg->setShippingType(self::NOT_SPECIFIED);
        $this->request_pg->setReference($this->uid);
        $this->request_pg->setCurrency('BRL');
    }

    public function add($id,$descricao=null,$quantidade=null,$custo=null,$peso=null,$custo_envio=null){
        $this->request_pg->addItem($id,$descricao,$quantidade,$custo,$peso,$custo_envio);
    }

    public function setReference($reference){
        $this->request_pg->setReference($reference);
    }

    public function setShippingType($type){
        $this->request_pg->setShippingType($type);
    }

    public function setSender($nome,$email=null,$area=null,$telefone=null,$tipo_documento=null,$documento=null){
        $this->request_pg->setSender($nome, $email, $area, $telefone, $tipo_documento, $documento);
    }

    public function setShippingAddress($cep,$logradouro=null,$numero=null,$complemento=null,$bairro=null,$cidade=null,$estado=null,$pais=null){
        $this->request_pg->setShippingAddress($cep,$logradouro,$numero,$complemento,$bairro,$cidade,$estado,$pais);
    }

    public function redirectURL($url){
        $url = Router::url($url, true);
        $this->request_pg->setRedirectUrl($url);
    }

    public function notificationURL($url){
        $url = Router::url($url, true);
        $this->request_pg->addParameter('notificationURL', $url);
    }

    public function addParameter($key, $value){
        $this->request_pg->addParameter($key, $value);
    }

    public function register($email=null,$token=null,$environment=null){

        $email = $email?$email:$this->config('email');
        $token = $token?$token:$this->config('token');
        $environment = $environment?$environment:$this->config('environment');

        $config = new \PagSeguroAccountCredentials($email, $token);
        \PagSeguroConfig::setEnvironment($environment?$environment:'production');
        return $this->request_pg->register($config);
    }

    public function checkTransaction($notificationCode,$email=null,$token=null){

        $email = $email?$email:$this->config('email');
        $token = $token?$token:$this->config('token');

        $config = new \PagSeguroAccountCredentials($email, $token);
        return \PagSeguroNotificationService::checkTransaction(
            $config,
            $notificationCode
        );
    }
}