<?php
namespace Pagseguro\Controller\Component;
use Cake\Controller\Component;
use Cake\Routing\Router;

class PagseguroComponent extends Component {

    const PAC = 1;
    const SEDEX = 2;
    const NOT_SPECIFIED = 3;

    public $request;
    public $uid;

    public function initialize(array $config) {
        parent::initialize($config);
        $this->uid = uniqid();
        $this->request = new \PagSeguroPaymentRequest;
        $this->request->setShippingType(self::NOT_SPECIFIED);
        $this->request->setReference($this->uid);
        $this->request->setCurrency('BRL');
    }

    public function add($id,$descricao=null,$quantidade=null,$custo=null,$peso=null,$custo_envio=null){
        $this->request->addItem($id,$descricao,$quantidade,$custo,$peso,$custo_envio);
    }

    public function setReference($reference){
        $this->request->setReference($reference);
    }

    public function setShippingType($type){
        $this->request->setShippingType($type);
    }

    public function setSender($nome,$email=null,$area=null,$telefone=null,$tipo_documento=null,$documento=null){
        $this->request->setSender($nome, $email, $area, $telefone, $tipo_documento, $documento);
    }

    public function setShippingAddress($cep,$logradouro=null,$numero=null,$complemento=null,$bairro=null,$cidade=null,$estado=null,$pais=null){
        $this->request->setShippingAddress($cep,$logradouro,$numero,$complemento,$bairro,$cidade,$estado,$pais);
    }

    public function redirectURL($url){
        $url = Router::url($url, true);
        $this->request->setRedirectUrl($url);
    }

    public function notificationURL($url){
        $url = Router::url($url, true);
        $this->request->addParameter('notificationURL', $url);
    }

    public function addParameter($key, $value){
        $this->request->addParameter($key, $value);
    }

    public function register($email=null,$token=null,$environment=null){

        $email = $email?$email:$this->config('email');
        $token = $token?$token:$this->config('token');
        $environment = $environment?$environment:$this->config('environment');

        $config = new \PagSeguroAccountCredentials($email, $token);
        \PagSeguroConfig::setEnvironment($environment?$environment:'production');
        return $this->request->register($config);
    }
}